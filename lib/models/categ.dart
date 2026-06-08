import 'menui.dart';
class Categ 
{final int id; final String name; final String slug; final String? desc; final int order; final List<Menui> menuItems;
Categ({required this.id, required this.name, required this.slug,required this.desc,required this.order,required this.menuItems });
factory Categ.fromJson(Map<String,dynamic>json){return Categ(id: json['id'],name: json['name'],slug: json['slug'],desc: json['description'],order: json['order']??0,menuItems: (json['menu_items']as List? ??[]).map((item)=>Menui.fromJson(item)).toList());}
}
