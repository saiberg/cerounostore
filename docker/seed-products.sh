#!/bin/sh
# Crea los productos que falten y asigna fotos de Pexels (licencia libre, uso comercial).
W="wp --allow-root --path=/var/www/html"
U="${WP_ADMIN_USER:-admin}"

cid() { $W term list product_cat --name="$1" --field=term_id </dev/null | head -n1; }

set_photo() { # id_producto id_foto_pexels nombre
  cur=$($W post meta get "$1" _cu_photo </dev/null 2>/dev/null || true)
  [ "$cur" = "$2" ] && return 0
  f="/tmp/pexels-$2.jpg"
  if ! wget -q -U "Mozilla/5.0" -O "$f" "https://images.pexels.com/photos/$2/pexels-photo-$2.jpeg?auto=compress&cs=tinysrgb&w=1000" </dev/null; then
    echo "  sin foto $2 para $3"; return 0
  fi
  aid=$($W media import "$f" --title="$3" --alt="$3" --porcelain </dev/null 2>/dev/null | tail -n1)
  if [ -n "$aid" ]; then
    $W post meta update "$1" _thumbnail_id "$aid" </dev/null >/dev/null
    $W post meta update "$1" _cu_photo "$2" </dev/null >/dev/null
  fi
  rm -f "$f"
}

while IFS='|' read -r name cat price sale feat photo desc; do
  [ -z "$name" ] && continue
  id=$($W post list --post_type=product --post_status=any --title="$name" --field=ID </dev/null | head -n1)
  if [ -z "$id" ]; then
    set -- --name="$name" --type=simple --regular_price="$price" --status=publish --featured="$feat" \
      --short_description="$desc" --description="$desc Despacho a todo Chile. Cambios y devoluciones simples." \
      --categories="[{\"id\":$(cid "$cat")}]" --user="$U" --porcelain
    [ -n "$sale" ] && set -- "$@" --sale_price="$sale"
    id=$($W wc product create "$@" </dev/null)
    echo "creado: $name ($id)"
  fi
  [ -n "$id" ] && set_photo "$id" "$photo" "$name"
done <<'EOF'
Audífonos Bluetooth TWS|Electrónica|23700|19000|true|3921864|Cancelación de ruido y estuche de carga.
Smartwatch Deportivo|Electrónica|38000||true|31406903|Monitor de ritmo cardiaco y notificaciones.
Cargador GaN 65W|Electrónica|20900|16600|false|35921904|Carga rápida USB-C para notebook y celular.
Organizador Apilable de Plástico|Plásticos y Organización|11900||true|4096909|Set de 3 cajas con tapa.
Juego de Contenedores Herméticos|Hogar y Cocina|18000|15100|false|13968346|10 piezas libres de BPA.
Mini Taladro Inalámbrico|Herramientas|28500||true|3877525|Incluye 20 puntas y estuche.
Lámpara LED de Escritorio|Oficina y Papelería|15200|12300|false|30211188|3 modos de luz y puerto USB.
Mochila Impermeable|Accesorios y Moda|26100||false|17491913|Compartimento para notebook de 15 pulgadas.
Audífonos Over-Ear Inalámbricos|Electrónica|29990|24990|false|7772548|Bluetooth 5.3 y hasta 30 horas de batería.
Power Bank 20.000 mAh|Electrónica|19990||false|3921704|Carga rápida con dos salidas USB y una USB-C.
Cámara de Seguridad WiFi|Electrónica|24990|19990|true|24347621|Resolución Full HD, visión nocturna y alertas en el celular.
Teclado Mecánico Compacto|Electrónica|34990||false|12561283|Switches mecánicos y retroiluminación.
Freidora de Aire 4 L|Hogar y Cocina|59990|49990|true|35285814|Cocina con poco aceite, temporizador y temperatura regulable.
Set de Cuchillos de Cocina|Hogar y Cocina|17990||false|12936944|Acero inoxidable, 6 piezas.
Hervidor Eléctrico 1.7 L|Hogar y Cocina|15990|12990|false|3847410|Apagado automático y base giratoria.
Canastos Plásticos Multiuso x3|Plásticos y Organización|9990||false|15336637|Resistentes y con asas, ideales para ordenar cualquier espacio.
Botella Deportiva Tritan 1 L|Plásticos y Organización|6990||false|17755959|Sin BPA, tapa hermética y boquilla antiderrame.
Organizador de Escritorio|Oficina y Papelería|11990|8990|false|8947691|Compartimentos para lápices, notas y accesorios.
Set de Destornilladores de Precisión|Herramientas|12990||false|5583097|Más de 30 puntas magnéticas para electrónica.
Huincha de Medir 5 m|Herramientas|3990||false|7778194|Cinta con freno y clip para el cinturón.
Linterna LED Recargable|Herramientas|8990|6990|false|18678168|Alta potencia, zoom y carga por USB.
Bloques de Construcción 500 piezas|Juguetes y Hobbies|16990||true|7123080|Compatible con las principales marcas del mercado.
Mini Drone con Cámara|Juguetes y Hobbies|39990|34990|false|13456575|Plegable, control desde el celular y vuelo estable.
Rompecabezas 1000 piezas|Juguetes y Hobbies|9990||false|9227507|Cartón de alta calidad con acabado mate.
Lentes de Sol Polarizados|Accesorios y Moda|12990||false|14090246|Protección UV400 y marco liviano.
Billetera de Cuero|Accesorios y Moda|14990|11990|false|27467367|Diseño delgado con compartimentos para tarjetas.
Cuaderno Tapa Dura A5|Oficina y Papelería|4990||false|7439131|Hojas lisas de 100 g y cierre con elástico.
Soporte de Aluminio para Notebook|Oficina y Papelería|19990||false|6372828|Ajustable en altura y plegable.
Soporte de Celular para Auto|Auto y Moto|7990||false|13536013|Fijación magnética firme y giro de 360 grados.
Aspiradora Portátil para Auto|Auto y Moto|21990|17990|false|4120479|Inalámbrica, con filtro lavable y boquillas.
EOF

# La foto de cada categoría es la del primer producto que contiene.
for row in $($W term list product_cat --fields=term_id,slug --format=csv | tail -n +2); do
  tid="${row%%,*}"; slug="${row#*,}"
  pid=$($W post list --post_type=product --"product_cat=$slug" --field=ID --orderby=ID --order=ASC --posts_per_page=1 2>/dev/null | head -n1)
  [ -z "$pid" ] && continue
  th=$($W post meta get "$pid" _thumbnail_id 2>/dev/null || true)
  [ -n "$th" ] && $W term meta update "$tid" thumbnail_id "$th" >/dev/null
done
echo "Productos: $($W post list --post_type=product --post_status=publish --format=count)"
