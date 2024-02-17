\c hrnavigator;
drop database commerce;
create database commerce;
\c commerce;

create table departement(
    id_departement serial primary key,
    libelle varchar(200)
);
ALTER TABLE departement
ADD COLUMN email VARCHAR(100);
ALTER TABLE departement
ADD COLUMN mot_passe VARCHAR(100);


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

create table typesortie(
    id_type serial primary key,
    types varchar(5) not null
);


insert into typesortie (types) values 
    ('FIFO'),
    ('LIFO'),
    ('Qmp');


create table produit(
    id_produit serial primary key,
    categorie_id int references categorie_materiel(id_categorie),
    libelle varchar(50) not null,
    unite_id int references unite(id_unite),
    typesortie_id int references typesortie(id_type)
);

create or replace view v_produit as
select p.*,
    u.libelle as unite,
    c.libelle as materiel,
    typesortie.types
from produit p 
join unite u on u.id_unite = p.unite_id
join categorie_materiel c on c.id_categorie = p.categorie_id
join typesortie on typesortie.id_type = p.typesortie_id;


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

INSERT INTO departement (libelle , email , mot_passe)
VALUES 
    ('Departement Ventes' , 'vente@gmail.com' , 'vente'),
    ('Departement Marketing et Communication' , 'mc@gmail.com' , 'mc'),
    ('Departement informatique' , 'informatioque@gmail.com' , 'info'),
    ('Departement RH' , 'rh@gmail.com' , 'rh');



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


insert into produit (categorie_id, libelle, unite_id, typesortie_id)
values 
    (1, 'Stylo bleu', 2, 1),
    (1, 'Stylo Rouge', 2, 3),
    (1, 'Stylo Vert', 2, 3),
    (1, 'Stylo Noir', 2, 1),
    (1, 'A4', 4, 2);
    

insert into produit (categorie_id, libelle, unite_id, typesortie_id)
values 
    (2, 'Ordinateur bureautique', 2, 1),
    (2, 'Ordinateur portable', 2, 1),
    (2, 'Imprimante', 2, 3);
    
insert into produit (categorie_id, libelle, unite_id , typesortie_id)
values 
    (3, 'Cache bouche', 3, 2),
    (3, 'Gan', 3, 2);


insert into fournisseur (libelle, adress, phone, email, mdp)
values 
    ('Hasinjo', 'Antsirabe', '+261 33 45 576 65', 'hasinjo@gmail.com', 'hasinjo'),
    ('Fanyah', 'Antananarivo', '+261 34 56 653 67', 'fanyah@gmail.com', 'fanyah'),
    ('Aina', 'Antananarivo', '+261 32 56 437 54', 'aina@gmail.com', 'aina');