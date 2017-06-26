

CREATE SEQUENCE assinaturaordenadordespesa_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;


CREATE SEQUENCE documentoassinaturaordenadordespesa_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;


create table assinaturaordenadordespesa (
  sequencial serial primary key not null,
  departamento integer,
  ativo boolean,
  nome varchar, 
  titulo varchar 
);


CREATE TABLE documentoassinaturaordenadordespesa(
sequencial		int4 NOT NULL default 0,
tipo		integer NOT NULL ,
chave		integer NOT NULL ,
assinaturaordenadordespesa		int4 default 0,
CONSTRAINT documentoassinaturaordenadordespesa_sequ_pk PRIMARY KEY (sequencial));

create index assinaturaordenadordespesa_departamento_in on assinaturaordenadordespesa(departamento);

create index documentoassinaturaordenadordespesa_chave_in on documentoassinaturaordenadordespesa(chave);
create index documentoassinaturaordenadordespesa_assinaturaordenadordespesa_in on documentoassinaturaordenadordespesa(assinaturaordenadordespesa);
ALTER table documentoassinaturaordenadordespesa
         add CONSTRAINT documentoassinaturaordenadordespesa_assinatura_fk FOREIGN key (assinaturaordenadordespesa)
         REFERENCES assinaturaordenadordespesa(sequencial);

