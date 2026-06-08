import 'dart:async';
import 'dart:convert';
import 'dart:typed_data';
import 'package:http/http.dart' as http;
import '../models/categ.dart';

class apise{static String? active;
static final imgs=<String,Future<Uint8List?>>{};
static List<String> get urls{const env=String.fromEnvironment('API_BASE_URL');if(env.isNotEmpty){return [env];}return ['https://backend-production-6121.up.railway.app/api'];}
static String secure(String value){return value.replaceFirst('http://backend-production-6121.up.railway.app','https://backend-production-6121.up.railway.app');}
static bool img(Uint8List b){if(b.length<12){return false;}final jpg=b[0]==0xff&&b[1]==0xd8;final png=b[0]==0x89&&b[1]==0x50&&b[2]==0x4e&&b[3]==0x47;final gif=b[0]==0x47&&b[1]==0x49&&b[2]==0x46;final webp=b[0]==0x52&&b[1]==0x49&&b[2]==0x46&&b[3]==0x46&&b[8]==0x57&&b[9]==0x45&&b[10]==0x42&&b[11]==0x50;return jpg||png||gif||webp;}
static String? imageUrl(String? path){if(path==null||path.isEmpty){return null;}final uri=Uri.tryParse(path);if(uri!=null&&uri.hasScheme){return path;}final base=active??urls.first;final clean=path.replaceFirst(RegExp(r'^/'),'').replaceFirst(RegExp(r'^storage/'),'');return '$base/menu-image/$clean';}
  Future<http.Response> req(String path,{String method='get',Object? body})async{final choices=active==null?urls:[active!,...urls.where((u)=>u!=active)];Object? last;for(final u in choices){try{var uri=Uri.parse('$u$path');late http.Response resp;if(method=='post'){resp=await http.post(uri,headers:{'Content-Type':'application/json'},body: body).timeout(Duration(seconds: 25));}else{uri=uri.replace(queryParameters:{...uri.queryParameters,'fresh':DateTime.now().millisecondsSinceEpoch.toString()});resp=await http.get(uri,headers:{'Cache-Control':'no-cache','Pragma':'no-cache'}).timeout(Duration(seconds: 15));}if(resp.statusCode<500){active=u;}return resp;}catch(e){last=e;}}throw Exception('API connection failed: $last');}
Future<Uint8List?> imageBytes(String url){final clean=secure(url);return imgs.putIfAbsent(clean,()async{try{final uri=Uri.parse(clean);final resp=await http.get(uri,headers:{'Accept':'image/webp,image/png,image/jpeg,image/*'}).timeout(Duration(seconds: 15));if(resp.statusCode!=200){return null;}final bytes=resp.bodyBytes;if(!img(bytes)){return null;}return bytes;}catch(e){return null;}});}
Future<List<Categ>> getcateg()async{final resp=await req('/categories'); if(resp.statusCode!=200){throw Exception('failed to load categories');}
final body=jsonDecode(resp.body);return (body['data']as List).map((item)=>Categ.fromJson(item)).toList();}
Future<String> menuver()async{final resp=await req('/menu-version'); if(resp.statusCode!=200){throw Exception('failed to check menu version');}final body=jsonDecode(resp.body);return body['version'].toString();}
Future<Map<String,dynamic>> createOrder(List<Map<String,dynamic>>items,String notes)async{final resp=await req('/orders',method:'post',body: jsonEncode({'items':items,'notes':notes}));final body=jsonDecode(resp.body);if(resp.statusCode!=201){throw Exception(body['message']??'failed to create order');}return body['data'];}
Future<Map<String,dynamic>> payOrder(int id,String method,double amount,{String? provider,String? reference,String? transaction})async{final data={'method':method,'amount':amount};if(method=='cash'){data['cash_received']=amount;}if(provider!=null){data['provider']=provider;}if(reference!=null){data['reference_number']=reference;}if(transaction!=null){data['transaction_id']=transaction;}final resp=await req('/orders/$id/pay',method:'post',body: jsonEncode(data));final body=jsonDecode(resp.body);if(resp.statusCode!=200){throw Exception(body['message']??'failed to pay order');}return body['data'];}}
