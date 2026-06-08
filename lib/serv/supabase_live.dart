import 'package:supabase_flutter/supabase_flutter.dart';

const supabaseUrl=String.fromEnvironment('SUPABASE_URL',defaultValue:'https://iaprdpxrzlpdttuhecug.supabase.co');
const supabaseAnonKey=String.fromEnvironment('SUPABASE_ANON_KEY');

bool get supabaseLiveEnabled=>supabaseUrl.isNotEmpty&&supabaseAnonKey.isNotEmpty;

Future<void> initSupabaseLive()async{if(supabaseLiveEnabled){await Supabase.initialize(url:supabaseUrl,anonKey:supabaseAnonKey);}}
