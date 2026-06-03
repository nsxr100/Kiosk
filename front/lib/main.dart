import 'package:flutter/material.dart';
import 'dart:async';
import 'package:front/screens/language.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return const MaterialApp(
      title: 'Kiosk App',
      debugShowCheckedModeBanner: false,
      home: MainPage(),
    );
  }
}

class MainPage extends StatefulWidget {
  const MainPage({
    super.key,
  });

  @override
  State<MainPage> createState() => _MainPageState();
}

class _MainPageState extends State<MainPage> {
  Timer? timer;
  @override void initState() {super.initState(); ls();}
  @override void dispose(){timer?.cancel();super.dispose();}
  @override
  Widget build(BuildContext context) {
    return Scaffold(backgroundColor: Color.fromARGB(255, 0, 107, 46),
      body: Center(
        child: Image(image: AssetImage('assets/inasal.png')),
      ),
    );
  }
  void ls(){timer=Timer(Duration(seconds: 3),(){if(!mounted){return;}Navigator.pushReplacement(context, MaterialPageRoute(builder: (context)=>Language()));});}
}
