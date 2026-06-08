class Menui 
{final int id; final int categid; final String name; final String? desc; final String basep; final String? imageUrl; final String? imageFullUrl; final String? imageDataUrl; final int order;final bool isA;
Menui
({required this.id,required this.categid,required this.name,required this.desc,required this.basep,required this.imageUrl,required this.imageFullUrl,required this.imageDataUrl,required this.order,required this.isA});
factory Menui.fromJson(Map<String,dynamic>json){return Menui(id: json['id'], categid: json['category_id'], name: json['name'], desc: json['description'], basep: json['base_price'].toString(), imageUrl: json['image_url'], imageFullUrl: json['image_full_url'], imageDataUrl: json['image_data_url'], order: json['order']??0, isA: json['is_active']??true);}

}
