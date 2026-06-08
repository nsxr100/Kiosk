import 'package:flutter/material.dart';
import '../data/lang.dart';
import 'receipt.dart';
import 'ewallet.dart';

class Payment extends StatefulWidget {
  final Map<String,dynamic> order;
  final String lang;
  const Payment({Key? key,required this.order,required this.lang}) : super(key: key);

  @override
  State<Payment> createState() => _PaymentState();
}

class _PaymentState extends State<Payment> {
  double amount(){return double.tryParse(widget.order['total_amount'].toString())??0;}
  void cash(){Navigator.pushReplacement(context,MaterialPageRoute(builder: (context)=>Receipt(order: widget.order,paid: false,lang: widget.lang)));}
  @override
  Widget build(BuildContext context) {
    return Scaffold(appBar: AppBar(title: Text(tr(widget.lang,'payment')),),
      body: Padding(padding: EdgeInsets.all(18),child: Column(crossAxisAlignment: CrossAxisAlignment.start,children: [
        Text(tr(widget.lang,'select_payment'),style: TextStyle(fontSize: 26,fontWeight: FontWeight.bold),),
        SizedBox(height: 8,),
        Text('\u20B1${amount().toStringAsFixed(2)}',style: TextStyle(fontSize: 28,fontWeight: FontWeight.bold,color: Color.fromARGB(255,0,107,46),),
        ),
        SizedBox(height: 28,),
        SizedBox(width: double.infinity,height: 58,child: ElevatedButton(style: ElevatedButton.styleFrom(backgroundColor: Color.fromARGB(255,0,150,55),foregroundColor: Colors.white,shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(6))),onPressed: cash,child: Text(tr(widget.lang,'pay_cash'),style: TextStyle(fontSize: 20,fontWeight: FontWeight.bold),),),),
        SizedBox(height: 14,),
        SizedBox(width: double.infinity,height: 58,child: OutlinedButton(onPressed: (){Navigator.push(context,MaterialPageRoute(builder: (context)=>Ewallet(order: widget.order,lang: widget.lang)));},child: Text(tr(widget.lang,'ewallet'),style: TextStyle(fontSize: 20,fontWeight: FontWeight.bold),),),),
      ],),),
    );
  }
}
