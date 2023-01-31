CREATE SEQUENCE seq_product
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807 START 1
CACHE 1;

CREATE SEQUENCE seq_user
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807 START 1
CACHE 1;

CREATE SEQUENCE seq_token
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807 START 1
CACHE 1;

CREATE SEQUENCE seq_orders
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807 START 1
CACHE 1;


CREATE SEQUENCE seq_order_items
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807 START 1
CACHE 1;

CREATE TABLE product (
  id integer NOT NULL DEFAULT nextval('seq_product'::regclass),
  "name" character varying(150) NOT NULL,
  description text NOT NULL,
  "value" decimal(18,2) NOT NULL,

  CONSTRAINT product_pkey PRIMARY KEY (id)
);

CREATE TABLE user_level (
  id integer NOT NULL,
  description character varying(50) NOT NULL,
  CONSTRAINT user_level_pkey PRIMARY KEY (id)
)
WITH (
  OIDS = FALSE
);

CREATE TABLE "user" (
  id integer NOT NULL DEFAULT nextval('seq_user'::regclass),
  "name" character varying(150) NOT NULL,
  username character varying(150) NOT NULL,
  "password" character varying(100) NULL,
  inserted_date TIMESTAMP DEFAULT NOW(),
  status character(1) NOT NULL DEFAULT 'A'::bpchar,
  user_level_id INTEGER NOT NULL,
  CONSTRAINT user_pkey PRIMARY KEY (id),
  CONSTRAINT user_user_level_id FOREIGN KEY (user_level_id) REFERENCES user_level (id)
)
WITH (
  OIDS = FALSE
);

INSERT INTO user_level (id, description) VALUES 
(10, 'ADMIN'),
(20, 'BACKOFFICE'),
(30, 'ADMIN EMPRESA'),
(50, 'CLIENTE');


CREATE TABLE "token" (
    "id" bigint NOT NULL DEFAULT nextval('seq_token'::regclass),
    "token" character varying(300) COLLATE pg_catalog. "default" NOT NULL,
    user_id integer NOT NULL,
    expires_in timestamp with time zone NOT NULL DEFAULT now(),
    CONSTRAINT token_pkey PRIMARY KEY (id),
    CONSTRAINT token_user_id_fkey FOREIGN KEY (user_id) REFERENCES "user" (id) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE NO ACTION)
  TABLESPACE pg_default;

CREATE UNIQUE INDEX token_users ON "token" USING btree ("token" COLLATE pg_catalog. "default" ASC NULLS LAST, user_id ASC NULLS LAST) TABLESPACE pg_default;

CREATE TABLE orders (
  id integer NOT NULL DEFAULT nextval('seq_orders'::regclass),
  user_id INTEGER NOT NULL,
  payment_method VARCHAR(255) NOT NULL,
  total NUMERIC(10,2) NOT NULL,
  change NUMERIC(10,2),
  status VARCHAR(2),
  customer_notes text,
  customer_name VARCHAR(255),
  CONSTRAINT orders_pkey PRIMARY KEY (id),
  CONSTRAINT orders_user_id FOREIGN KEY (user_id) REFERENCES "user" (id)

)
WITH (
  OIDS = FALSE
);

CREATE TABLE order_items (
    id integer NOT NULL DEFAULT nextval('seq_order_items'::regclass),
    order_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL,
    quantity INTEGER NOT NULL,
	CONSTRAINT order_item_pkey PRIMARY KEY (id),
	CONSTRAINT order_item_order_id FOREIGN KEY (order_id) REFERENCES "orders" (id),
	CONSTRAINT order_item_product_id FOREIGN KEY (product_id) REFERENCES "product" (id)
);


INSERT INTO product (name, description, value)
VALUES ('Hamburguer artesanal de cheddar ','Um delicioso hamburguer que contém 150g de carne de alcatra e muito cheddar', 15.00), 
		('Big Burguer','Um delicioso hamburguer que contém 300g de carne , queijo , cebola roxa', 45.00),
		('Coca Cola','Coca Cola de 2L', 10.00),
		('Coca Cola','Coca Cola de 600ML', 6.00);
