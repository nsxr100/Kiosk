
import 'package:flutter/material.dart';
import 'package:front/screens/cout.dart';
import '../serv/apis.dart';
import '../models/categ.dart';
import '../models/menui.dart';
import '../data/images.dart';
import '../data/lang.dart';

class Menu extends StatefulWidget {
  final String lang;
  const Menu({Key? key,required this.lang}) : super(key: key);

  @override
  State<Menu> createState() => _MenuState();
}

class _MenuState extends State<Menu> {
  late final Future<List<Categ>> categs;
  int sel=0;
  bool search=false;
  String q='';
  final cart=<int,Menui>{};
  final qty=<int,int>{};
  final sctrl=TextEditingController();
  @override void initState(){super.initState(); categs=apise().getcateg();}
  @override void dispose(){sctrl.dispose();super.dispose();}
  void cats(List<Categ>data){showModalBottomSheet(context: context,builder: (context){return ListView.builder(itemCount: data.length,itemBuilder: (context,index){final cat=data[index];return ListTile(title: Text(cat.name),onTap: (){setState((){sel=index;});Navigator.pop(context);},);});});}
  int tq(){return qty.values.fold(0,(a,b)=>a+b);}
  double total(){double t=0;cart.forEach((id,item){t+=(double.tryParse(item.basep)??0)*(qty[id]??0);});return t;}
  void pick(Menui item){int n=qty[item.id]??1;showModalBottomSheet(context: context,builder: (context){return StatefulBuilder(builder: (context,setb){return Padding(padding: EdgeInsets.all(18),child: Column(mainAxisSize: MainAxisSize.min,crossAxisAlignment: CrossAxisAlignment.start,children: [Center(child: SizedBox(height: 140,child: Image.asset(menuImage(item.name),fit: BoxFit.contain,errorBuilder: (context,error,stack){return Image.asset('assets/inasal.png',fit: BoxFit.contain);},),),),SizedBox(height: 14,),Text(item.name,style: TextStyle(fontSize: 22,fontWeight: FontWeight.bold),),Text('\u20B1${item.basep}',style: TextStyle(fontSize: 18,color: Color.fromARGB(255, 0, 107, 46),fontWeight: FontWeight.bold),),SizedBox(height: 18,),Row(mainAxisAlignment: MainAxisAlignment.center,children: [IconButton(onPressed: (){if(n>0){setb((){n--;});}},icon: Icon(Icons.remove_circle_outline,size: 34),),SizedBox(width: 24,),Text('$n',style: TextStyle(fontSize: 28,fontWeight: FontWeight.bold),),SizedBox(width: 24,),IconButton(onPressed: (){setb((){n++;});},icon: Icon(Icons.add_circle_outline,size: 34,color: Color.fromARGB(255,0,107,46)),),],),SizedBox(height: 18,),SizedBox(width: double.infinity,height: 54,child: ElevatedButton(style: ElevatedButton.styleFrom(backgroundColor: Color.fromARGB(255, 0, 150, 55),foregroundColor: Colors.white,shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(6))),onPressed: (){setState((){if(n<=0){cart.remove(item.id);qty.remove(item.id);}else{cart[item.id]=item;qty[item.id]=n;}});Navigator.pop(context);},child: Text(n<=0?tr(widget.lang,'remove'):tr(widget.lang,'add_to_order'),style: TextStyle(fontSize: 20,fontWeight: FontWeight.bold),),),),],),);});});}
  void order(List<Categ>data){if(cart.isEmpty){ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(tr(widget.lang,'select_first')),duration: Duration(seconds: 1),));return;}final menuItems=data.expand((cat)=>cat.menuItems).toList();Navigator.push(context,MaterialPageRoute(builder: (context)=>Cout(cart: cart,qty: qty,total: total(),lang: widget.lang,menuItems: menuItems)));}
  @override
  Widget build(BuildContext context) {
    return Scaffold(appBar: AppBar(automaticallyImplyLeading: true,title: Column(crossAxisAlignment: CrossAxisAlignment.start,children: [Text(tr(widget.lang,'pickup'), style: TextStyle(color: Color.fromARGB(255, 0, 107, 46),fontSize: 18,fontWeight: FontWeight.bold),)],),),
      body: FutureBuilder<List<Categ>>(future: categs, builder: (context,snapshot){if(snapshot.connectionState==ConnectionState.waiting){return const Center(child: CircularProgressIndicator(),);}if(snapshot.hasError){return Center(child: Text('error: ${snapshot.error}'),);}
      final data=snapshot.data??[]; if(sel>=data.length){sel=0;} final items=data.isNotEmpty?data[sel].menuItems:[]; final shown=q.isEmpty?items:items.where((item)=>item.name.toLowerCase().contains(q.toLowerCase())).toList();
      return Column(children: [
        Padding(padding: EdgeInsets.fromLTRB(20, 50, 20, 16),child: Column(crossAxisAlignment: CrossAxisAlignment.start,children: [
          Text(tr(widget.lang,'menu_title'), style: TextStyle(fontSize: 30,fontWeight: FontWeight.bold),),SizedBox(height: 28,),
          if(search)TextField(controller: sctrl,autofocus: true,onChanged: (value){setState((){q=value;});},decoration: InputDecoration(hintText: tr(widget.lang,'search_menu'),suffixIcon: IconButton(icon: Icon(Icons.close),onPressed: (){setState((){search=false;q='';sctrl.clear();});}),),),if(search)SizedBox(height: 18,),
          SizedBox(height: 45,child:ListView(scrollDirection: Axis.horizontal,children: [InkWell(onTap: (){setState((){search=true;});},child: Icon(Icons.search,size: 34,)),SizedBox(width: 25,),InkWell(onTap: (){cats(data);},child: Icon(Icons.list,size: 34,)),SizedBox(width: 25,),
          ...data.asMap().entries.map((entry){final i=entry.key; final cat=entry.value; return Padding(padding: EdgeInsets.only(right: 28),child: InkWell(onTap: (){setState((){sel=i;});},child: Text(cat.name,style: TextStyle(fontSize: 20,fontWeight: FontWeight.bold,color: sel==i?Color.fromARGB(255,0,107, 64):Colors.grey),),),);}),
          ],),)
        ],),),Divider(height: 1,), Expanded(child: shown.isEmpty?Center(child: Text(tr(widget.lang,'no_items'),)):GridView.builder(padding:EdgeInsets.all(18), gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(crossAxisCount: 2,childAspectRatio: .72,crossAxisSpacing: 20,mainAxisSpacing: 25),itemCount: shown.length ,itemBuilder: (context,index)
          {final item=shown[index]; return InkWell(onTap: (){pick(item);},child: Column(crossAxisAlignment: CrossAxisAlignment.start,children: [Expanded(child: Container(color: Colors.grey.shade200,child:Stack(children:[Center(child: Image.asset(menuImage(item.name),fit: BoxFit.contain,errorBuilder: (context,error,stack){return Image.asset('assets/inasal.png',fit: BoxFit.contain);},)),if(qty[item.id]!=null)Positioned(right: 6,top: 6,child: CircleAvatar(radius: 14,backgroundColor: Color.fromARGB(255,0,107,46),child: Text('${qty[item.id]}',style: TextStyle(color: Colors.white,fontSize: 13,fontWeight: FontWeight.bold),),),)],) ,)),SizedBox(height: 10,),Text(item.name,maxLines: 2,overflow: TextOverflow.ellipsis,style: TextStyle(fontSize: 18,fontWeight: FontWeight.bold),),Text('\u20B1${item.basep}',style: TextStyle(fontSize: 18,color: Color.fromARGB(255, 0, 107, 46),fontWeight: FontWeight.bold),)],));})),
          Padding(padding:EdgeInsets.all(18),child: SizedBox(width: double.infinity,height: 58,child: ElevatedButton(style: ElevatedButton.styleFrom(backgroundColor: Color.fromARGB(255, 0, 150, 55),foregroundColor: Colors.white,shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(6))),onPressed: (){order(data);}, child: Text(cart.isEmpty?tr(widget.lang,'order_now'):'${tr(widget.lang,'order_now')} (${tq()}) - \u20B1${total().toStringAsFixed(2)}',style: TextStyle(fontSize: 22,fontWeight: FontWeight.bold),)),),)
      ],); 
      })
    );
  }
}
