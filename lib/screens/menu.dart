
import 'dart:async';
import 'dart:typed_data';
import 'package:flutter/material.dart';
import 'package:front/screens/cout.dart';
import 'package:supabase_flutter/supabase_flutter.dart';
import '../serv/apis.dart';
import '../serv/supabase_live.dart';
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
  Future<List<Categ>>? categs;
  RealtimeChannel? live;
  Timer? poll;
  List<Categ>? cache;
  String? ver;
  int sel=0;
  bool search=false;
  String q='';
  final cart=<int,Menui>{};
  final qty=<int,int>{};
  final sctrl=TextEditingController();
  @override void initState(){super.initState(); load(); listen(); poll=Timer.periodic(Duration(seconds: 3),(timer){if(!supabaseLiveEnabled){check();}});}
  void load(){categs=apise().getcateg();apise().menuver().then((value){ver=value;}).catchError((error){});}
  void reload(){if(mounted){setState((){load();});}}
  void check(){apise().menuver().then((value){if(ver==null){ver=value;return;}if(value!=ver){ver=value;reload();}}).catchError((error){});}
  void liveReload(){reload();Timer(Duration(milliseconds: 1200),(){reload();});}
  void listen(){if(!supabaseLiveEnabled){return;}live=Supabase.instance.client.channel('menu-live').onPostgresChanges(event: PostgresChangeEvent.all,schema: 'public',table: 'menu_items',callback: (payload){liveReload();}).onPostgresChanges(event: PostgresChangeEvent.all,schema: 'public',table: 'categories',callback: (payload){liveReload();}).onPostgresChanges(event: PostgresChangeEvent.all,schema: 'public',table: 'menu_variants',callback: (payload){liveReload();}).subscribe();}
  @override void dispose(){poll?.cancel();if(live!=null){Supabase.instance.client.removeChannel(live!);}sctrl.dispose();super.dispose();}
  Widget itemImage(Menui item){final fallback=menuImage(item.name);final remote=item.imageFullUrl??apise.imageUrl(item.imageUrl);if(remote!=null){return LiveMenuImage(url: apise.secure(remote),fallback: fallback,uploaded: true,);}return Image.asset(fallback,fit: BoxFit.contain,errorBuilder: (context,error,stack){return Image.asset('assets/inasal.png',fit: BoxFit.contain);},);}
  void cats(List<Categ>data){showModalBottomSheet(context: context,builder: (context){return ListView.builder(itemCount: data.length,itemBuilder: (context,index){final cat=data[index];return ListTile(title: Text(cat.name),onTap: (){setState((){sel=index;});Navigator.pop(context);},);});});}
  int tq(){return qty.values.fold(0,(a,b)=>a+b);}
  double total(){double t=0;cart.forEach((id,item){t+=(double.tryParse(item.basep)??0)*(qty[id]??0);});return t;}
  bool hide(Menui item){return item.name.toLowerCase()=='mushroom gravy';}
  void pick(Menui item){int n=qty[item.id]??1;showModalBottomSheet(context: context,builder: (context){return StatefulBuilder(builder: (context,setb){return Padding(padding: EdgeInsets.all(18),child: Column(mainAxisSize: MainAxisSize.min,crossAxisAlignment: CrossAxisAlignment.start,children: [Center(child: SizedBox(height: 140,child: itemImage(item),),),SizedBox(height: 14,),Text(item.name,style: TextStyle(fontSize: 22,fontWeight: FontWeight.bold),),Text('\u20B1${item.basep}',style: TextStyle(fontSize: 18,color: Color.fromARGB(255, 0, 107, 46),fontWeight: FontWeight.bold),),SizedBox(height: 18,),Row(mainAxisAlignment: MainAxisAlignment.center,children: [IconButton(onPressed: (){if(n>0){setb((){n--;});}},icon: Icon(Icons.remove_circle_outline,size: 34),),SizedBox(width: 24,),Text('$n',style: TextStyle(fontSize: 28,fontWeight: FontWeight.bold),),SizedBox(width: 24,),IconButton(onPressed: (){setb((){n++;});},icon: Icon(Icons.add_circle_outline,size: 34,color: Color.fromARGB(255,0,107,46)),),],),SizedBox(height: 18,),SizedBox(width: double.infinity,height: 54,child: ElevatedButton(style: ElevatedButton.styleFrom(backgroundColor: Color.fromARGB(255, 0, 150, 55),foregroundColor: Colors.white,shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(6))),onPressed: (){setState((){if(n<=0){cart.remove(item.id);qty.remove(item.id);}else{cart[item.id]=item;qty[item.id]=n;}});Navigator.pop(context);},child: Text(n<=0?tr(widget.lang,'remove'):tr(widget.lang,'add_to_order'),style: TextStyle(fontSize: 20,fontWeight: FontWeight.bold),),),),],),);});});}
  Future<void> order(List<Categ>data)async{if(cart.isEmpty){ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(tr(widget.lang,'select_first')),duration: Duration(seconds: 1),));return;}final menuItems=data.expand((cat)=>cat.menuItems).toList();await Navigator.push(context,MaterialPageRoute(builder: (context)=>Cout(cart: cart,qty: qty,total: total(),lang: widget.lang,menuItems: menuItems)));if(mounted){setState((){});}}
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(child: FutureBuilder<List<Categ>>(future: categs,initialData: cache, builder: (context,snapshot){if(snapshot.connectionState==ConnectionState.waiting&&!snapshot.hasData){return const Center(child: CircularProgressIndicator(),);}if(snapshot.hasError&&!snapshot.hasData){return Center(child: Text('error: ${snapshot.error}'),);}
      if(snapshot.hasData){cache=snapshot.data;}
      final data=snapshot.data??[]; if(sel>=data.length){sel=0;} final items=data.isNotEmpty?data[sel].menuItems.where((item)=>!hide(item)).toList():[]; final shown=q.isEmpty?items:items.where((item)=>item.name.toLowerCase().contains(q.toLowerCase())).toList();
      return Column(children: [
        Padding(padding: EdgeInsets.fromLTRB(20, 10, 20, 16),child: Column(crossAxisAlignment: CrossAxisAlignment.start,children: [
          Text(tr(widget.lang,'menu_title'), style: TextStyle(fontSize: 30,fontWeight: FontWeight.bold),),SizedBox(height: 14,),
          if(search)TextField(controller: sctrl,autofocus: true,onChanged: (value){setState((){q=value;});},decoration: InputDecoration(hintText: tr(widget.lang,'search_menu'),suffixIcon: IconButton(icon: Icon(Icons.close),onPressed: (){setState((){search=false;q='';sctrl.clear();});}),),),if(search)SizedBox(height: 18,),
          SizedBox(height: 45,child:ListView(scrollDirection: Axis.horizontal,children: [SizedBox(width: 42,height: 45,child: Center(child: InkWell(onTap: (){setState((){search=true;});},child: Icon(Icons.search,size: 34),),),),SizedBox(width: 18,),SizedBox(width: 42,height: 45,child: Center(child: InkWell(onTap: (){cats(data);},child: Icon(Icons.list,size: 34),),),),SizedBox(width: 18,),
          ...data.asMap().entries.map((entry){final i=entry.key; final cat=entry.value; return Padding(padding: EdgeInsets.only(right: 28),child: Center(child: InkWell(onTap: (){setState((){sel=i;});},child: Text(cat.name,style: TextStyle(fontSize: 20,fontWeight: FontWeight.bold,color: sel==i?Color.fromARGB(255,0,107, 64):Colors.grey),),),),);}),
          ],),)
        ],),),Divider(height: 1,), Expanded(child: shown.isEmpty?Center(child: Text(tr(widget.lang,'no_items'),)):GridView.builder(padding:EdgeInsets.all(18), gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(crossAxisCount: 2,childAspectRatio: .62,crossAxisSpacing: 16,mainAxisSpacing: 18),itemCount: shown.length ,itemBuilder: (context,index)
          {final item=shown[index]; return InkWell(onTap: (){pick(item);},borderRadius: BorderRadius.circular(8),child: Container(decoration: BoxDecoration(color: Colors.white,borderRadius: BorderRadius.circular(8),border: Border.all(color: Colors.grey.shade300),boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: .05),blurRadius: 5,offset: Offset(0,2),),],),child: Padding(padding: EdgeInsets.all(10),child: Column(crossAxisAlignment: CrossAxisAlignment.start,children: [Expanded(child: Stack(children:[Center(child: itemImage(item),),if(qty[item.id]!=null)Positioned(right: 0,top: 0,child: CircleAvatar(radius: 14,backgroundColor: Color.fromARGB(255,0,107,46),child: Text('${qty[item.id]}',style: TextStyle(color: Colors.white,fontSize: 13,fontWeight: FontWeight.bold),),),)],),),SizedBox(height: 12,),Text(item.name,maxLines: 2,overflow: TextOverflow.ellipsis,style: TextStyle(fontSize: 16,fontWeight: FontWeight.w600),),SizedBox(height: 8,),Text('\u20B1${item.basep}',style: TextStyle(fontSize: 17,color: Color.fromARGB(255, 0, 107, 46),fontWeight: FontWeight.bold),)],),),),);})),
          Padding(padding:EdgeInsets.all(18),child: SizedBox(width: double.infinity,height: 58,child: ElevatedButton(style: ElevatedButton.styleFrom(backgroundColor: Color.fromARGB(255, 0, 150, 55),foregroundColor: Colors.white,shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(6))),onPressed: (){order(data);}, child: Text(cart.isEmpty?tr(widget.lang,'order_now'):'${tr(widget.lang,'order_now')} (${tq()}) - \u20B1${total().toStringAsFixed(2)}',style: TextStyle(fontSize: 22,fontWeight: FontWeight.bold),)),),)
      ],); 
      }),)
    );
  }
}

class LiveMenuImage extends StatefulWidget {
  final String url;
  final String fallback;
  final bool uploaded;
  const LiveMenuImage({super.key,required this.url,required this.fallback,required this.uploaded});

  @override
  State<LiveMenuImage> createState() => _LiveMenuImageState();
}

class _LiveMenuImageState extends State<LiveMenuImage> {
  late Future<Uint8List?> bytes;

  @override void initState(){super.initState();bytes=apise().imageBytes(widget.url);}
  @override void didUpdateWidget(covariant LiveMenuImage oldWidget){super.didUpdateWidget(oldWidget);if(oldWidget.url!=widget.url){bytes=apise().imageBytes(widget.url);}}

  @override
  Widget build(BuildContext context) {
    return FutureBuilder<Uint8List?>(future: bytes,builder: (context,snapshot){final data=snapshot.data;if(data!=null){return Image.memory(data,fit: BoxFit.contain,errorBuilder: (context,error,stack){return blank();},);}if(widget.uploaded){return loading();}return Image.asset(widget.fallback,fit: BoxFit.contain,errorBuilder: (context,error,stack){return Image.asset('assets/inasal.png',fit: BoxFit.contain);},);});
  }

  Widget blank(){return SizedBox.expand();}
  Widget loading(){return Center(child: SizedBox(width: 26,height: 26,child: CircularProgressIndicator(strokeWidth: 2,color: Color.fromARGB(255,0,107,46),),),);}
}
