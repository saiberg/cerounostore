#!/bin/sh
# Instalación idempotente de WordPress + WooCommerce + tema cerounostore.
set -e
cd /var/www/html
WP="wp --allow-root --path=/var/www/html"
URL="http://localhost:${WP_PORT}"

echo "Esperando archivos de WordPress..."
until [ -f wp-config.php ]; do sleep 2; done
until $WP db check >/dev/null 2>&1; do sleep 3; done

if ! $WP core is-installed 2>/dev/null; then
  $WP core install --url="$URL" --title="$WP_SITE_TITLE" \
    --admin_user="$WP_ADMIN_USER" --admin_password="$WP_ADMIN_PASSWORD" \
    --admin_email="$WP_ADMIN_EMAIL" --skip-email

  $WP language core install es_ES --activate || true
  $WP option update timezone_string "America/Santiago"
  $WP rewrite structure '/%postname%/' --hard

  $WP plugin install woocommerce --activate
  $WP language plugin install woocommerce es_ES || true
  $WP theme activate cerounostore

  # Ajustes de tienda
  $WP option update woocommerce_currency "CLP"
  $WP option update woocommerce_default_country "CL"
  $WP option update woocommerce_price_num_decimals 0
  $WP option update woocommerce_price_thousand_sep "."
  $WP option update woocommerce_price_decimal_sep ","
  $WP option update woocommerce_calc_taxes "no"
  $WP option update woocommerce_onboarding_opt_in "no" || true
  $WP option update woocommerce_task_list_hidden "yes" || true
  $WP wc --user="$WP_ADMIN_USER" tool run install_pages >/dev/null 2>&1 || true

  # Categorías
  for c in "Electrónica" "Hogar y Cocina" "Plásticos y Organización" "Herramientas" "Juguetes y Hobbies" "Accesorios y Moda" "Oficina y Papelería" "Auto y Moto"; do
    $WP wc product_cat create --name="$c" --user="$WP_ADMIN_USER" >/dev/null 2>&1 || true
  done

  # Página de inicio y menú
  $WP post create --post_type=page --post_title="Inicio" --post_status=publish --porcelain >/tmp/home_id
  $WP option update show_on_front page
  $WP option update page_on_front "$(cat /tmp/home_id)"
  $WP post create --post_type=page --post_title="Contacto" --post_status=publish \
    --post_content="Escríbenos a ${WP_ADMIN_EMAIL}." >/dev/null

  $WP menu create "Principal" >/dev/null
  $WP menu item add-custom principal "Inicio" "$URL/" >/dev/null
  $WP menu item add-post principal "$($WP post list --post_type=page --name=contacto --field=ID | head -n1)" --title="Contacto" >/dev/null
  $WP menu location assign principal primary

  $WP rewrite flush --hard
fi

# Productos y fotos (idempotente)
sh /seed-products.sh || true

chown -R 33:33 /var/www/html/wp-content
echo "Listo: $URL  (admin: $URL/wp-admin)"
