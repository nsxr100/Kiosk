import 'package:flutter/material.dart';
import '../serv/apis.dart';
import '../data/lang.dart';
import 'receipt.dart';

class Ewallet extends StatefulWidget {
  final Map<String,dynamic> order;
  final String lang;
  const Ewallet({Key? key,required this.order,required this.lang}) : super(key: key);

  @override
  State<Ewallet> createState() => _EwalletState();
}

class _EwalletState extends State<Ewallet> {
  String platform='GCash';
  final sender=TextEditingController();
  final mobile=TextEditingController();
  final amount=TextEditingController();
  bool loading=false;
  final platforms=['GCash','Maya','ShopeePay','GrabPay'];
  @override void initState(){super.initState();amount.text=due().toStringAsFixed(2);}
  @override void dispose(){sender.dispose();mobile.dispose();amount.dispose();super.dispose();}
  double due(){return double.tryParse(widget.order['total_amount'].toString())??0;}
  Future<void> pay()async{final a=double.tryParse(amount.text)??0;if(sender.text.trim().isEmpty||mobile.text.trim().isEmpty){ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(tr(widget.lang,'fill_details')),));return;}if(a<due()){ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(tr(widget.lang,'amount_low')),));return;}setState((){loading=true;});
  try{final data=await apise().payOrder(widget.order['id'],'gcash',a,provider: platform,transaction: '${sender.text.trim()} - ${mobile.text.trim()}');if(!mounted)return;Navigator.pushReplacement(context,MaterialPageRoute(builder: (context)=>Receipt(order: data,paid: true,lang: widget.lang)));}catch(e){if(!mounted)return;ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString().replaceFirst('Exception: ','')),));}finally{if(mounted){setState((){loading=false;});}}}
  @override
  Widget build(BuildContext context) {
    return Scaffold(appBar: AppBar(title: Text(tr(widget.lang,'ewallet')),),
      body: ListView(padding: EdgeInsets.all(18),children: [
        Text(tr(widget.lang,'select_wallet'),style: TextStyle(fontSize: 22,fontWeight: FontWeight.bold),),
        SizedBox(height: 12,),
        Wrap(spacing: 10,runSpacing: 10,children: platforms.map((p)=>ChoiceChip(label: Text(p),selected: platform==p,selectedColor: Color.fromARGB(255, 0, 150, 55),labelStyle: TextStyle(color: platform==p?Colors.white:Colors.black,fontWeight: FontWeight.bold),onSelected: (v){setState((){platform=p;});},)).toList(),),
        SizedBox(height: 28,),
        Text(tr(widget.lang,'payment_details'),style: TextStyle(fontSize: 22,fontWeight: FontWeight.bold),),
        SizedBox(height: 12,),
        Text('${tr(widget.lang,'amount_due')}: \u20B1${due().toStringAsFixed(2)}',style: TextStyle(fontSize: 18,color: Color.fromARGB(255,0,107,46),fontWeight: FontWeight.bold),),
        SizedBox(height: 18,),
        TextField(controller: sender,decoration: InputDecoration(labelText: tr(widget.lang,'sender_name'),border: OutlineInputBorder(),),),
        SizedBox(height: 14,),
        TextField(controller: mobile,keyboardType: TextInputType.phone,decoration: InputDecoration(labelText: tr(widget.lang,'mobile_number'),border: OutlineInputBorder(),),),
        SizedBox(height: 14,),
        TextField(controller: amount,keyboardType: TextInputType.numberWithOptions(decimal: true),decoration: InputDecoration(labelText: tr(widget.lang,'amount_send'),border: OutlineInputBorder(),prefixText: '\u20B1 ',),),
        SizedBox(height: 24,),
        SizedBox(width: double.infinity,height: 58,child: ElevatedButton(style: ElevatedButton.styleFrom(backgroundColor: Color.fromARGB(255, 0, 150, 55),foregroundColor: Colors.white,shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(6))),onPressed: loading?null:pay,child: loading?SizedBox(width: 24,height: 24,child: CircularProgressIndicator(color: Colors.white,strokeWidth: 3),):Text(tr(widget.lang,'pay_now'),style: TextStyle(fontSize: 22,fontWeight: FontWeight.bold),),),),
      ],),
    );
  }
}
