/* table stock */
ALTER TABLE stock MODIFY cantidad DECIMAL(16,2);

/* table kardex */
ALTER TABLE kardex MODIFY stock_anterior DECIMAL(16,2);
ALTER TABLE kardex MODIFY stock_actual DECIMAL(16,2);

/* table detalle_mov_almacen */
ALTER TABLE detalle_mov_almacen MODIFY cantidad DECIMAL(16,2);

/* option unidad_medida*/
INSERT INTO menuoption VALUES(NULL, 'Unidades de Medida', 'unidadmedida', 4, 9, '2022-11-10 17:10:06', '2022-11-10 17:10:06', NULL);
INSERT INTO permission VALUES(NULL, 1, (SELECT id FROM menuoption ORDER by id DESC LIMIT 1), '2022-11-10 17:10:06', '2022-11-10 17:10:06');

/*
migration unidad_medida
----------------------------------------------------
php artisan make:migration crear_tabla_unidad_medida
php artisan migrate
php artisan make:model Unidadmedida
php artisan make:controller UnidadmedidaController

llave foranea de unidad de medida en producto
-------------------------------------------------
php artisan make:migration add_foreign_key_producto_table
*/

/* Valores iniciales unidades de medida */
INSERT INTO `unidad_medida` VALUES (NULL, 'UND', 'UNIDADES', 0, '2022-11-10 18:11:01', '2022-11-10 18:11:01', NULL);
INSERT INTO `unidad_medida` VALUES (NULL, 'MTR', 'METROS', 1, '2022-11-10 18:14:10', '2022-11-10 18:14:10', NULL);

