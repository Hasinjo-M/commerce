\c hrnavigator;
drop database commerce;
create database commerce;
\c commerce;

create table departement(
    id_departement serial primary key,
    libelle varchar(100) not null
); 

create table services(
    id_service serial primary key,
    libelle varchar(100) not null
);

create table entreprise (
    id_entreprise serial primary key,
    nom varchar(100) not null,
    adress varchar(100) not null,
    phone varchar(100) not null,
    email varchar(100) not null
);

create table utilisateur(
    id_utilisateur serial primary key,
    nom varchar(70) not null,
    prenoms varchar(70) not null,
    email varchar(100) not null,
    mdp varchar(25) not null,
    status_entreprise int not null,
    type_status int not null
);

create table categorie_materiel(
    id_categorie serial primary key,
    libelle varchar(50) not null
);

create table unite(
    id_unite serial primary key,
    libelle varchar(10) not null
);




create table produit(
    id_produit serial primary key,
    categorie_id int references categorie_materiel(id_categorie),
    libelle varchar(50) not null,
    unite_id int references unite(id_unite)
);

create or replace view v_produit as
select p.*,
    u.libelle as unite,
    c.libelle as materiel
from produit p 
join unite u on u.id_unite = p.unite_id
join categorie_materiel c on c.id_categorie = p.categorie_id;


create table demande(
    id_demande serial primary key,
    departement_id int references departement(id_departement),
    produit_id int references produit(id_produit),
    quantite double precision not null,
    justificatif text null,
    date_demande date default now(),
    numero_groupe int default 0 
);

create or replace view v_demande as
select d.*,
    dep.libelle as departement, 
    c.libelle as materiel, c.id_categorie as cat_id,
    p.libelle as produit,
    u.libelle as unite
from demande d 
join departement dep on dep.id_departement = d.departement_id
join produit p on p.id_produit = d.produit_id
join unite u on u.id_unite = p.unite_id
join categorie_materiel c on c.id_categorie = p.categorie_id
;

select numero_groupe,materiel,produit, sum(quantite) from v_demande where numero_groupe != 0 group by numero_groupe,materiel,produit;

create sequence numero_groupe;

create table fournisseur(
    id_fournisseur serial primary key,
    libelle varchar (100) not null,
    adress varchar(100) not null,
    phone varchar(100) not null,
    email varchar(100) not null,
    mdp varchar(28) not null
);

create table email(
    id_email serial primary key,
    date_email date default now(),
    groupe_numero int,
    fournisseur_id int references fournisseur(id_fournisseur),
    object_email varchar(200) null,
    descriptions_email text 
);


create table proforma(
    id_proforma serial primary key, 
    date_proformat date default now(),
    groupe_numero int not null,
    fournisseur_id int references fournisseur(id_fournisseur),
    produit_id int references produit(id_produit),
    prix_uniatire double precision not null,
    tax int default 1,
    taux_tax double precision default 20,
    remise double precision default 0,
    quantite double precision default 0,
    descriptions text
);


create or replace view v_proforma as
select pro.*,
    f.libelle as fournisseur,
    p.libelle as designation, p.materiel, p.unite,
    (prix_uniatire*quantite) as ht, 
     (prix_uniatire*quantite) + (( ((prix_uniatire*quantite) * taux_tax)/ 100 ) * tax) as ttc
from proforma pro
join fournisseur f on f.id_fournisseur = pro.fournisseur_id
join v_produit p on p.id_produit = pro.produit_id ;


create table proforma_group (
    id_proforma_group serial primary key,
    proforma_id int references proforma(id_proforma)
);

create or replace view v_proforma_group as
select * 
from v_proforma
join proforma_group on proforma_group.proforma_id = v_proforma.id_proforma; 


select date_proformat, fournisseur_id,prix_uniatire, tax, taux_tax,quantite, 
    (prix_uniatire*quantite) as ht, 
     (prix_uniatire*quantite) + (( ((prix_uniatire*quantite) * taux_tax)/ 100 ) * tax) as ttc
from v_proforma
order by date_proformat, fournisseur_id, quantite, ttc;


insert into entreprise (nom, adress, phone, email)
values
    ('HEntreprise', 'Andoharanofotsy 102', '+261 38 90 133 58', 'hentreprise@gmail.com');

INSERT INTO departement (libelle)
VALUES 
    ('Departement Ventes'),
    ('Departement Marketing et Communication'),
    ('Departement informatique'),
    ('Departement RH');

insert into services (libelle)
values 
    ('Achat et vente');


insert into utilisateur(nom, prenoms, email, mdp, status_entreprise, type_status)
values 
    ('Hasinjo', 'Toavina', 'toavinahasnii02@gmail.com', 'toavina', 2, 1),
    ('Mirantsoa', 'Fanyah', 'fanyah@gmail.com', 'fanyah', 1, 1),
    ('Navalona', 'Aina', 'aina@gmail.com', 'aina', 1, 3);


insert into categorie_materiel (libelle)
values
    ('Materiel Bureautique'),
    ('Materiel Informatique'),
    ('Materiel Medical');

insert into unite (libelle)
values
    ('kg'),
    ('pce'),
    ('Boite'),
    ('Ramette');


insert into produit (categorie_id, libelle, unite_id)
values 
    (1, 'Stylo bleu', 2),
    (1, 'Stylo Rouge', 2),
    (1, 'Stylo Vert', 2),
    (1, 'Stylo Noir', 2),
    (1, 'A4', 4);
    

insert into produit (categorie_id, libelle, unite_id)
values 
    (2, 'Ordinateur bureautique', 2),
    (2, 'Ordinateur portable', 2),
    (2, 'Imprimante', 2);
    
insert into produit (categorie_id, libelle, unite_id)
values 
    (3, 'Cache bouche', 3),
    (3, 'Gan', 3);


insert into fournisseur (libelle, adress, phone, email, mdp)
values 
    ('Hasinjo', 'Antsirabe', '+261 33 45 576 65', 'hasinjo@gmail.com', 'hasinjo'),
    ('Fanyah', 'Antananarivo', '+261 34 56 653 67', 'fanyah@gmail.com', 'fanyah'),
    ('Aina', 'Antananarivo', '+261 32 56 437 54', 'aina@gmail.com', 'aina');


CREATE TABLE bon_commande_mere (
    id_bon_commande_mere SERIAL PRIMARY KEY,
    fournisseur_id INT REFERENCES fournisseur(id_fournisseur),
    group_id INT,
    numero_commande VARCHAR(60) NOT NULL UNIQUE,
    etat INT DEFAULT 0
);

CREATE SEQUENCE commande_sequence START 1;

CREATE OR REPLACE FUNCTION generate_numero_commande()
RETURNS TRIGGER AS $$
BEGIN
    NEW.numero_commande := 'COMMANDE_' || LPAD(CAST(nextval('commande_sequence') AS VARCHAR), 4, '0');
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER before_insert_bon_commande
BEFORE INSERT ON bon_commande_mere
FOR EACH ROW
EXECUTE FUNCTION generate_numero_commande();


create table bon_commande(
    id_bon_commande serial primary key,
    bon_commande_mere_id int references bon_commande_mere(id_bon_commande_mere),
    produit_id int references produit(id_produit),
    proforma_mere int references proforma(id_proforma),
    quantite double precision not null,
    prix_uniatire double precision not null,
    tax double precision default 0
);


create or REPLACE view v_bon_commande as
select * from 
bon_commande
join bon_commande_mere 
    on bon_commande_mere.id_bon_commande_mere = bon_commande.bon_commande_mere_id;


create or replace view v_proforma_valider as 
select vb.*,
    p.fournisseur,p.designation, p.materiel, p.unite, 
    (vb.prix_uniatire * vb.quantite) as ht , 
    ( (vb.prix_uniatire * vb.quantite) + ( (vb.prix_uniatire * vb.quantite) * p.taux_tax  )/100 ) as ttc
from v_bon_commande vb
join v_proforma p on p.id_proforma  = vb.proforma_mere;



create table proforma_modif(
    id_proforma_modif serial primary key, 
    proforma_mere int references proforma(id_proforma),
    date_proformat date default now(),
    groupe_numero int not null,
    fournisseur_id int references fournisseur(id_fournisseur),
    produit_id int references produit(id_produit),
    prix_uniatire double precision not null,
    tax int default 1,
    taux_tax double precision default 20,
    remise double precision default 0,
    quantite double precision default 0,
    descriptions text
);


create table bon_livraison_mere(
    id_bon_livraison_mere serial primary key,
    numero_livraison varchar(60) not null unique,
    fournisseur_id int references fournisseur(id_fournisseur),
    date_livraison date  null,
    numero_commande varchar(60) references bon_commande_mere(numero_commande),
    lieu text  null,
    etat int default 0
); 


create or replace view v_bon_livraison_mere as
select * 
from bon_livraison_mere
join fournisseur on fournisseur.id_fournisseur = bon_livraison_mere.fournisseur_id; 


CREATE SEQUENCE livraison_sequence START 1;

CREATE OR REPLACE FUNCTION generate_numero_livraison()
RETURNS TRIGGER AS $$
BEGIN
    NEW.numero_livraison := 'LIVRAISON_' || LPAD(CAST(nextval('livraison_sequence') AS VARCHAR), 4, '0');
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER before_insert_bon_livraison
BEFORE INSERT ON bon_livraison_mere
FOR EACH ROW
EXECUTE FUNCTION generate_numero_livraison();


create table bon_livraison(
    id_bon_livraison serial primary key,
    bon_livraison_mere_id int references bon_livraison_mere(id_bon_livraison_mere),
    produit_id int references produit(id_produit),
    quantite double precision not null,
    prix_uniatire double precision not null,
    tax double precision default 0
);


create or replace  view v_bon_livraison  as
select bon_livraison.*,v_bon_livraison_mere.*,
p.libelle as designation, p.unite, p.materiel
from bon_livraison 
join v_bon_livraison_mere on v_bon_livraison_mere.id_bon_livraison_mere = bon_livraison.bon_livraison_mere_id
join v_produit p on p.id_produit = bon_livraison.produit_id;



----------------- Facture -----------------------------

create table bon_reception_mere(
    id_bon_reception_mere serial primary key,
    numero_reception varchar(60) not null unique,
    responsable varchar(70) null,
    date_reception date null,
    livraison_numero varchar(60) references bon_livraison_mere(numero_livraison),
    etat int default 0,
    descriptions text null
);


CREATE SEQUENCE reception_sequence START 1;

CREATE OR REPLACE FUNCTION generate_numero_reception()
RETURNS TRIGGER AS $$
BEGIN
    NEW.numero_reception := 'RECEPTION_' || LPAD(CAST(nextval('reception_sequence') AS VARCHAR), 4, '0');
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER before_insert_bon_reception
BEFORE INSERT ON bon_reception_mere
FOR EACH ROW
EXECUTE FUNCTION generate_numero_reception();


create table bon_reception(
    id_bon_reception serial primary key,
    bon_reception_mere_id int references bon_reception_mere (id_bon_reception_mere),
    produit_id int references produit(id_produit),
    quantite double precision not null,
    prix_uniatire double precision not null,
    tax double precision default 0
);

create or replace view v_bon_reception as
select * 
from bon_reception
join bon_reception_mere on bon_reception_mere.id_bon_reception_mere = bon_reception.bon_reception_mere_id
join v_produit on v_produit.id_produit = bon_reception.produit_id;


create table bon_entrer_mere(
    id_bon_entrer_mere serial primary key,
    numero_entrer varchar(60) not null unique, 
    reception_numero varchar(60) references bon_reception_mere(numero_reception),
    responsable varchar(100) null,
    date_entrer date  null,
    etat int default 0
);



CREATE SEQUENCE entrer_sequence START 1;
CREATE OR REPLACE FUNCTION generate_numero_entrer()
RETURNS TRIGGER AS $$
BEGIN
    NEW.numero_entrer := 'ENTRER_' || LPAD(CAST(nextval('entrer_sequence') AS VARCHAR), 4, '0');
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER before_insert_bon_entrer
BEFORE INSERT ON bon_entrer_mere
FOR EACH ROW
EXECUTE FUNCTION generate_numero_entrer();


create table bon_entrer(
    id_bon_entrer serial primary key,
    bon_entrer_mere_id int references bon_entrer_mere(id_bon_entrer_mere),
    produit_id int references produit(id_produit),
    quantite double precision not null,
    prix_uniatire double precision not null,
    tax double precision default 0 
);

create table magasin(
    id_magasin serial primary key,
    magasin varchar(100) not null,
    email varchar(100) not null,
    mdp varchar(50) not null
);

insert into magasin values(default, 'Magasin', 'm@gmail.com', 'magasin');


/************* Stock *************/
select  l.bon_livraison_mere_id, e.produit_id, e.quantite, 
    l.prix_uniatire
from bon_entrer e 
join bon_livraison l on l.bon_livraison_mere_id = e.bon_livraison_mere_id and l.produit_id = e.produit_id;



delete from entrant;
delete from mouvement;
delete from sortie_mere;
delete from sortie;