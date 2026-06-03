import 'dart:async';
import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/categ.dart';

class apise{static String? active;
static List<String> get urls{const env=String.fromEnvironment('API_BASE_URL');if(env.isNotEmpty){return [env];}return ['https://backend-production-6121.up.railway.app/api'];}
  Future<http.Response> req(String path,{String method='get',Object? body})async{final choices=active==null?urls:[active!,...urls.where((u)=>u!=active)];Object? last;for(final u in choices){try{final uri=Uri.parse('$u$path');late http.Response resp;if(method=='post'){resp=await http.post(uri,headers:{'Content-Type':'application/json'},body: body).timeout(Duration(seconds: 25));}else{resp=await http.get(uri).timeout(Duration(seconds: 15));}if(resp.statusCode<500){active=u;}return resp;}catch(e){last=e;}}throw Exception('API connection failed: $last');}
Future<List<Categ>> getcateg()async{final resp=await req('/categories'); if(resp.statusCode!=200){throw Exception('failed to load categories');}
final body=jsonDecode(resp.body);return (body['data']as List).map((item)=>Categ.fromJson(item)).toList();}
Future<Map<String,dynamic>> createOrder(List<Map<String,dynamic>>items,String notes)async{final resp=await req('/orders',method:'post',body: jsonEncode({'items':items,'notes':notes}));final body=jsonDecode(resp.body);if(resp.statusCode!=201){throw Exception(body['message']??'failed to create order');}return body['data'];}
Future<Map<String,dynamic>> payOrder(int id,String method,double amount,{String? provider,String? reference,String? transaction})async{final data={'method':method,'amount':amount};if(method=='cash'){data['cash_received']=amount;}if(provider!=null){data['provider']=provider;}if(reference!=null){data['reference_number']=reference;}if(transaction!=null){data['transaction_id']=transaction;}final resp=await req('/orders/$id/pay',method:'post',body: jsonEncode(data));final body=jsonDecode(resp.body);if(resp.statusCode!=200){throw Exception(body['message']??'failed to pay order');}return body['data'];}}
