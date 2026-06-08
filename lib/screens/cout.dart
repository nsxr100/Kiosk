import 'package:flutter/material.dart';
import '../models/menui.dart';
import '../serv/apis.dart';
import '../data/lang.dart';
import 'payment.dart';

class Cout extends StatefulWidget {
  final Map<int,Menui> cart;
  final Map<int,int> qty;
  final double total;
  final String lang;
  final List<Menui> menuItems;
  Cout({Key? key,required this.cart,required this.qty,required this.total,required this.lang,required this.menuItems}) : super(key: key);

  @override
  _CoutState createState() => _CoutState();
}

class _CoutState extends State<Cout> {
  late Map<int,Menui> cart;
  late Map<int,int> qty;
  final addons=<int,List<String>>{};
  final drinks=<int,String>{};
  final notes=TextEditingController();
  final addonPrices={'Java Rice':40.0,'Chicken Oil':7.0,'Soup':11.0,'Toyomansi':7.0,'Spiced Vinegar':8.0};
  final drinkPrices={'Coke':44.0,'Coke Zero':44.0,'Sprite':44.0,'Iced Tea':44.0,'Iced Red Gulaman':44.0};
  bool loading=false;
  @override void initState(){super.initState();cart=widget.cart;qty=widget.qty;}
  @override void dispose(){notes.dispose();super.dispose();}
  Menui? findMenu(String name){for(final item in widget.menuItems){if(item.name.toLowerCase()==name.toLowerCase()){return item;}}return null;}
  bool hasDrink(Menui item){final n=item.name.toLowerCase();if(n.contains('with drink')){return true;}for(final d in drinkPrices.keys){final x=d.toLowerCase();if(n==x||n.startsWith('$x ')){return true;}}return false;}
  double extra(Menui item){double e=0;for(final a in addons[item.id]??[]){e+=addonPrices[a]??0;}final d=drinks[item.id];if(d!=null&&d!=tr(widget.lang,'no_drink')){e+=drinkPrices[d]??0;}return e;}
  double total(){double t=0;cart.forEach((id,item){t+=((double.tryParse(item.basep)??0)+extra(item))*(qty[id]??0);});return t;}
  int count(){return qty.values.fold(0,(a,b)=>a+b);}
  String priceText(double p){return '+\u20B1${p.toStringAsFixed(2)}';}
  void add(Menui item){setState((){qty[item.id]=(qty[item.id]??0)+1;});}
  void minus(Menui item){setState((){final q=(qty[item.id]??0)-1;if(q<=0){qty.remove(item.id);cart.remove(item.id);addons.remove(item.id);drinks.remove(item.id);}else{qty[item.id]=q;}});}
  void remove(Menui item){setState((){qty.remove(item.id);cart.remove(item.id);addons.remove(item.id);drinks.remove(item.id);});}
  String addonText(Menui item){final a=addons[item.id]??[];final d=drinks[item.id];final parts=<String>[];if(a.isNotEmpty){parts.add('${tr(widget.lang,'addons')}: ${a.map((x)=>'$x (${priceText(addonPrices[x]??0)})').join(', ')}');}if(d!=null&&d!=tr(widget.lang,'no_drink')){parts.add('${tr(widget.lang,'drinks')}: $d (${priceText(drinkPrices[d]??0)})');}return parts.isEmpty?tr(widget.lang,'tap_addons'):parts.join(' | ');}
  String? lineNote(Menui item){final a=addons[item.id]??[];final d=drinks[item.id];final parts=<String>[];if(a.isNotEmpty){parts.add('Add-ons: ${a.map((x)=>'$x (${priceText(addonPrices[x]??0)})').join(', ')}');}if(d!=null&&d!=tr(widget.lang,'no_drink')){parts.add('Drink: $d (${priceText(drinkPrices[d]??0)})');}return parts.isEmpty?null:parts.join(' | ');}
  void addonSheet(Menui item){final opts=addonPrices.keys.toList();final drinkopts=[tr(widget.lang,'no_drink'),...drinkPrices.keys];final selected=List<String>.from(addons[item.id]??[]);final showDrink=!hasDrink(item);String drink=drinks[item.id]??drinkopts[0];showModalBottomSheet(context: context,isScrollControlled: true,builder: (context){return StatefulBuilder(builder: (context,setb){return Padding(padding: EdgeInsets.fromLTRB(18,18,18,18+MediaQuery.of(context).viewInsets.bottom),child: SingleChildScrollView(child: Column(mainAxisSize: MainAxisSize.min,crossAxisAlignment: CrossAxisAlignment.start,children: [Text(item.name,style: TextStyle(fontSize: 22,fontWeight: FontWeight.bold),),SizedBox(height: 8,),Text(tr(widget.lang,'addons'),style: TextStyle(fontSize: 18,color: Color.fromARGB(255, 0, 107, 46),fontWeight: FontWeight.bold),),...opts.map((op)=>CheckboxListTile(value: selected.contains(op),title: Text('$op (${priceText(addonPrices[op]??0)})'),controlAffinity: ListTileControlAffinity.leading,onChanged: (v){setb((){if(v==true){selected.add(op);}else{selected.remove(op);}});},)),if(showDrink)SizedBox(height: 8,),if(showDrink)Text(tr(widget.lang,'drinks'),style: TextStyle(fontSize: 18,color: Color.fromARGB(255, 0, 107, 46),fontWeight: FontWeight.bold),),if(showDrink)SizedBox(height: 8,),if(showDrink)DropdownButtonFormField<String>(initialValue: drink,decoration: InputDecoration(border: OutlineInputBorder(),),items: drinkopts.map((d)=>DropdownMenuItem(value: d,child: Text(d==tr(widget.lang,'no_drink')?d:'$d (${priceText(drinkPrices[d]??0)})'),)).toList(),onChanged: (v){setb((){drink=v??drinkopts[0];});},),SizedBox(height: 18,),Row(children: [Expanded(child: OutlinedButton(onPressed: (){remove(item);Navigator.pop(context);},child: Text(tr(widget.lang,'remove_order')),),),SizedBox(width: 12,),Expanded(child: ElevatedButton(style: ElevatedButton.styleFrom(backgroundColor: Color.fromARGB(255, 0, 150, 55),foregroundColor: Colors.white),onPressed: (){setState((){addons[item.id]=selected;if(showDrink){drinks[item.id]=drink;}else{drinks.remove(item.id);}});Navigator.pop(context);},child: Text(tr(widget.lang,'save')),),),],)],),),);});});}
  List<Map<String,dynamic>> orderItems(){final list=<Map<String,dynamic>>[];for(final item in cart.values){final q=qty[item.id]??1;list.add({'menu_item_id':item.id,'quantity':q,'notes':lineNote(item)});for(final a in addons[item.id]??[]){final addItem=findMenu(a);if(addItem!=null){list.add({'menu_item_id':addItem.id,'quantity':q,'notes':'Add-on for ${item.name}'});}}final d=drinks[item.id];if(d!=null&&d!=tr(widget.lang,'no_drink')){final drinkItem=findMenu(d);if(drinkItem!=null){list.add({'menu_item_id':drinkItem.id,'quantity':q,'notes':'Drink for ${item.name}'});}}}return list;}
  Future<void> confirm()async{if(cart.isEmpty){Navigator.pop(context);return;}setState((){loading=true;});try{final order=await apise().createOrder(orderItems(),notes.text);if(!mounted)return;Navigator.pushReplacement(context,MaterialPageRoute(builder: (context)=>Payment(order: order,lang: widget.lang)));}catch(e){if(!mounted)return;ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString().replaceFirst('Exception: ','')),));}finally{if(mounted){setState((){loading=false;});}}}
  @override
  Widget build(BuildContext context) {
    final items=cart.values.toList();
    return Scaffold(appBar: AppBar(title: Text(tr(widget.lang,'checkout')),),
      body: Column(children: [
        Expanded(child: cart.isEmpty?Center(child: Text(tr(widget.lang,'cart_empty'),)):ListView.builder(itemCount: items.length,itemBuilder: (context,index){final item=items[index];final q=qty[item.id]??0;final price=double.tryParse(item.basep)??0;final line=(price+extra(item))*q;return InkWell(onTap: (){addonSheet(item);},child: Padding(padding: EdgeInsets.fromLTRB(18, 12, 18, 12),child: Column(crossAxisAlignment: CrossAxisAlignment.start,children: [Row(crossAxisAlignment: CrossAxisAlignment.start,children: [Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start,children: [Text(item.name,style: TextStyle(fontSize: 16,fontWeight: FontWeight.bold),),SizedBox(height: 4,),Text(addonText(item),style: TextStyle(color: Colors.grey.shade700),),],),),Text('\u20B1${line.toStringAsFixed(2)}',style: TextStyle(fontWeight: FontWeight.bold),),],),SizedBox(height: 8,),Row(children: [Text('\u20B1${price.toStringAsFixed(2)}'),if(extra(item)>0)Text(' + \u20B1${extra(item).toStringAsFixed(2)}',style: TextStyle(color: Color.fromARGB(255, 0, 107, 46),fontWeight: FontWeight.bold),),Spacer(),IconButton(onPressed: (){minus(item);},icon: Icon(Icons.remove_circle_outline),),Text('$q',style: TextStyle(fontSize: 18,fontWeight: FontWeight.bold),),IconButton(onPressed: (){add(item);},icon: Icon(Icons.add_circle_outline,color: Color.fromARGB(255, 0, 107, 46),),),IconButton(onPressed: (){remove(item);},icon: Icon(Icons.delete_outline,color: Colors.red),),],),Divider(height: 1,),],),),);},),),
        Padding(padding: EdgeInsets.all(18),child: Column(children: [
          TextField(controller: notes,decoration: InputDecoration(labelText: tr(widget.lang,'order_notes'),border: OutlineInputBorder(),),),
          SizedBox(height: 18,),
          Row(mainAxisAlignment: MainAxisAlignment.spaceBetween,children: [Text(tr(widget.lang,'items'),style: TextStyle(fontSize: 18),),Text('${count()}',style: TextStyle(fontSize: 18,fontWeight: FontWeight.bold),),],),
          SizedBox(height: 8,),
          Row(mainAxisAlignment: MainAxisAlignment.spaceBetween,children: [Text(tr(widget.lang,'total'),style: TextStyle(fontSize: 22,fontWeight: FontWeight.bold),),Text('\u20B1${total().toStringAsFixed(2)}',style: TextStyle(fontSize: 22,fontWeight: FontWeight.bold,color: Color.fromARGB(255, 0, 107, 46)),),],),
          SizedBox(height: 18,),
          SizedBox(width: double.infinity,height: 58,child: ElevatedButton(style: ElevatedButton.styleFrom(backgroundColor: Color.fromARGB(255, 0, 150, 55),foregroundColor: Colors.white,shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(6))),onPressed: loading?null:confirm, child: loading?SizedBox(width: 24,height: 24,child: CircularProgressIndicator(color: Colors.white,strokeWidth: 3),):Text(tr(widget.lang,'confirm_order'),style: TextStyle(fontSize: 22,fontWeight: FontWeight.bold),)),),
        ],),)
      ],),
    );
  }
}
