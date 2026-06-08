import 'package:flutter/material.dart';
import 'package:front/screens/menu.dart';
import '../data/lang.dart';

class Language extends StatelessWidget {
  const Language({Key? key}) : super(key: key);

  void sl(BuildContext context,String lc){Navigator.pushReplacement(context,MaterialPageRoute(builder: (context)=>Menu(lang: lc)));}
  Widget langbtn(BuildContext context,String title,String sub,String lc){return SizedBox(width: double.infinity,height: 72,child: ElevatedButton(style: ElevatedButton.styleFrom(backgroundColor: Colors.white,foregroundColor: Color.fromARGB(255,0,107,46),elevation: 0,shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8))),onPressed: (){sl(context,lc);},child: Row(children: [CircleAvatar(radius: 18,backgroundColor: Color.fromARGB(255,0,107,46),child: Text(title.substring(0,1),style: TextStyle(color: Colors.white,fontWeight: FontWeight.bold),),),SizedBox(width: 16,),Expanded(child: Column(mainAxisAlignment: MainAxisAlignment.center,crossAxisAlignment: CrossAxisAlignment.start,children: [Text(title,style: TextStyle(fontSize: 20,fontWeight: FontWeight.bold),),Text(sub,style: TextStyle(fontSize: 13,color: Colors.grey.shade700),),],),),Icon(Icons.arrow_forward_ios,size: 18,color: Color.fromARGB(255,0,107,46),),],),),);}

  @override
  Widget build(BuildContext context) {
    return Scaffold(backgroundColor: Color.fromARGB(255, 0, 107, 46),
      body: SafeArea(child: Padding(padding: EdgeInsets.all(28),child: Column(children: [
        Spacer(),
        SizedBox(width: 150,height: 150,child: Image.asset('assets/inasal.png',fit: BoxFit.contain),),
        SizedBox(height: 28,),
        Text(tr('en','select_language'),style: TextStyle(color: Colors.white,fontSize: 30,fontWeight: FontWeight.bold),),
        SizedBox(height: 8,),
        Text('Choose your preferred app language',textAlign: TextAlign.center,style: TextStyle(color: Colors.white.withValues(alpha: .85),fontSize: 15),),
        SizedBox(height: 36,),
        langbtn(context,'English','Use English as the app language','en'),
        SizedBox(height: 14,),
        langbtn(context,'Filipino','Gamitin ang Filipino sa app','fil'),
        Spacer(),
        Text('Mang Inasal Ordering System',style: TextStyle(color: Colors.white.withValues(alpha: .75),fontSize: 13,fontWeight: FontWeight.bold),),
      ],),),),
    );
  }
}
