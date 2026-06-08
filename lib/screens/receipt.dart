import 'package:flutter/material.dart';
import '../data/lang.dart';
import 'menu.dart';

class Receipt extends StatefulWidget {
  final Map<String,dynamic> order;
  final bool paid;
  final String lang;
  const Receipt({Key? key,required this.order,required this.paid,required this.lang}) : super(key: key);

  @override
  State<Receipt> createState() => _ReceiptState();
}

class _ReceiptState extends State<Receipt> {
  late Map<String,dynamic> order;
  late bool paid;
  @override void initState(){super.initState();order=widget.order;paid=widget.paid;}
  double amount(){return double.tryParse(order['total_amount'].toString())??0;}
  void start(){Navigator.pushAndRemoveUntil(context,MaterialPageRoute(builder: (context)=>Menu(lang: widget.lang)),(route)=>false);}
  @override
  Widget build(BuildContext context) {
    final items=(order['items']as List? ??[]);
    return Scaffold(
      body: SafeArea(child: Column(children: [
        Expanded(child: ListView(padding: EdgeInsets.all(18),children: [
          SizedBox(height: 28,),
          Center(child: Text(paid?tr(widget.lang,'paid'):tr(widget.lang,'order_received'),style: TextStyle(fontSize: 26,fontWeight: FontWeight.bold,color: Color.fromARGB(255, 0, 107, 46)),),),
          SizedBox(height: 8,),
          Center(child: Text(order['order_number']?.toString()??'',style: TextStyle(fontSize: 16,fontWeight: FontWeight.bold),),),
          if(order['receipt_number']!=null)Center(child: Text(order['receipt_number'].toString(),style: TextStyle(color: Colors.grey.shade700),),),
          SizedBox(height: 24,),
          ...items.map((item){final q=item['quantity']??0;final name=item['item_name']?.toString()??'';final line=double.tryParse(item['line_total'].toString())??0;return ListTile(contentPadding: EdgeInsets.zero,title: Text(name),subtitle: Text('${tr(widget.lang,'qty')}: $q'),trailing: Text('\u20B1${line.toStringAsFixed(2)}',style: TextStyle(fontWeight: FontWeight.bold),),);}),
          Divider(height: 32,),
          Row(mainAxisAlignment: MainAxisAlignment.spaceBetween,children: [Text(tr(widget.lang,'subtotal')),Text('\u20B1${(double.tryParse(order['subtotal'].toString())??0).toStringAsFixed(2)}'),],),
          SizedBox(height: 8,),
          Row(mainAxisAlignment: MainAxisAlignment.spaceBetween,children: [Text(tr(widget.lang,'total'),style: TextStyle(fontSize: 22,fontWeight: FontWeight.bold),),Text('\u20B1${amount().toStringAsFixed(2)}',style: TextStyle(fontSize: 22,fontWeight: FontWeight.bold),),],),
          SizedBox(height: 12,),
          Text('${tr(widget.lang,'status')}: ${order['status']}',style: TextStyle(color: Colors.grey.shade700),),
          Text('${tr(widget.lang,'payment')}: ${order['payment_status']}',style: TextStyle(color: Colors.grey.shade700),),
        ],),),
        Padding(padding: EdgeInsets.all(18),child: SizedBox(width: double.infinity,height: 54,child: ElevatedButton(onPressed: start,style: ElevatedButton.styleFrom(backgroundColor: Color.fromARGB(255, 0, 150, 55),foregroundColor: Colors.white,shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(6))),child: Text(tr(widget.lang,'back_start'),style: TextStyle(fontSize: 18,fontWeight: FontWeight.bold),),),),)
      ],),),
    );
  }
}
