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

create or replace view v_bon_entrer as 
select *
from bon_entrer 
join bon_entrer_mere on bon_entrer_mere.id_bon_entrer_mere = bon_entrer.bon_entrer_mere_id
join v_produit on v_produit.id_produit = bon_entrer.produit_id;


create table magasin(
    id_magasin serial primary key,
    magasin varchar(100) not null,
    email varchar(100) not null,
    mdp varchar(50) not null
);

insert into magasin values(default, 'Magasin', 'm@gmail.com', 'magasin');


/************* Stock *************/
create table entrant(
    id_entrant serial primary key,
    date_entrant date not null,
    produit_id int references produit(id_produit),
    unite_id int references unite(id_unite),
    quantite double precision not null,
    reste double precision not null,
    prix_unitaire double precision not null,
    magasin_id int references magasin(id_magasin)
);


create or replace view v_entrant as
select p.*,
    e.date_entrant, e.quantite, e.reste, e.prix_unitaire , (e.quantite*e.prix_unitaire) as montant
from entrant e
join v_produit p on p.id_produit = e.produit_id;



create table sortie_mere(
    id_sortie_mere serial primary key,
    date_sortie date null default now(),
    produit_id int references produit(id_produit),
    magasin_id int references magasin(id_magasin),
    quantite_total double precision not null,
    status_cloture int default 0,
    date_validation date null
);


create or replace view v_non_valider as
select * from 
sortie_mere 
join v_produit on v_produit.id_produit = sortie_mere.produit_id
join magasin on magasin.id_magasin = sortie_mere.magasin_id
where status_cloture < 20 and status_cloture >=10 ; 


create or replace view v_sortie_valider as 
select * from 
sortie_mere 
join v_produit on v_produit.id_produit = sortie_mere.produit_id
join magasin on magasin.id_magasin = sortie_mere.magasin_id
where  status_cloture >=20 ; 


create or replace view v_prix_sortie as
select sum(prix_unitaire * q_sortant),sortie_mere_id 
from sortie group by sortie_mere_id;

create table sortie  (
    id_sortie serial primary key,
    date_sortie date not null,
    q_init double precision not null, 
    q_sortant double precision not null,
    prix_unitaire double precision not null,
    sortie_mere_id int references sortie_mere(id_sortie_mere),
    entrant_id int references entrant(id_entrant)
);




create table mouvement(
    id_mouvement serial primary key,
    date_etat date not null,
    produit_id int references produit(id_produit),
    magasin_id int references magasin(id_magasin),
    quantite double precision not null,
    montant double precision not null
);


create or replace view v_etat_stock as
select m.*,
    ma.magasin, p.libelle, p.types, p.unite, p.materiel
from mouvement m
join magasin ma on ma.id_magasin = m.magasin_id
join v_produit p on p.id_produit = m.produit_id;


SELECT
    m.*,
    ma.magasin,
    p.libelle,
    p.types,
    p.unite,
    p.materiel
FROM
    mouvement m
JOIN magasin ma ON ma.id_magasin = m.magasin_id
JOIN v_produit p ON p.id_produit = m.produit_id
WHERE
    (m.produit_id, m.id_mouvement) IN (
        SELECT
            produit_id,
            MAX(id_mouvement) AS max_id_mouvement
        FROM
            mouvement
        WHERE
            date_etat BETWEEN '2023-12-11' AND '2023-12-25'
        GROUP BY
            produit_id
    );



CREATE OR REPLACE FUNCTION mouvement_entrant()
RETURNS TRIGGER AS $$
DECLARE
    last_etat mouvement;
    date_etat DATE; 
    montant DOUBLE PRECISION;
    quantite DOUBLE PRECISION;
BEGIN
    SELECT *
    INTO last_etat
    FROM mouvement
    WHERE produit_id = NEW.produit_id AND magasin_id = NEW.magasin_id
    ORDER BY date_etat DESC
    LIMIT 1;

    IF last_etat IS NOT NULL THEN
        date_etat := NEW.date_entrant;
        quantite := last_etat.quantite + NEW.quantite;
        montant := last_etat.montant + (NEW.prix_unitaire * NEW.quantite);
    ELSE
        date_etat := NEW.date_entrant;
        montant := NEW.prix_unitaire * NEW.quantite;
        quantite := NEW.quantite;
    END IF;

    INSERT INTO mouvement (date_etat, produit_id, magasin_id, quantite, montant)
    VALUES (date_etat, NEW.produit_id, NEW.magasin_id, quantite, montant);

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


CREATE TRIGGER mouvement_entrant_trigger
AFTER INSERT ON entrant
FOR EACH ROW
EXECUTE FUNCTION mouvement_entrant();


CREATE OR REPLACE FUNCTION mouvement_sortant()
RETURNS TRIGGER AS $$
DECLARE
    last_etat mouvement;
    date_etat DATE; 
    montant DOUBLE PRECISION;
    quantite DOUBLE PRECISION;
    mere sortie_mere;
BEGIN
    select * into mere from sortie_mere where id_sortie_mere = NEW.sortie_mere_id;

    SELECT *
    INTO last_etat
    FROM mouvement
    WHERE produit_id = mere.produit_id AND magasin_id = mere.magasin_id
    ORDER BY id_mouvement DESC
    LIMIT 1;

    IF last_etat IS NOT NULL THEN
        date_etat := NEW.date_sortie;
        quantite := last_etat.quantite - NEW.q_sortant;
        montant := last_etat.montant - (NEW.prix_unitaire * NEW.q_sortant);
    ELSE
       RAISE EXCEPTION 'Il n y a pas de stock de ce produit';
    END IF;

    INSERT INTO mouvement (date_etat, produit_id, magasin_id, quantite, montant)
    VALUES (date_etat, mere.produit_id, mere.magasin_id, quantite, montant);

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;



CREATE TRIGGER mouvement_sortie_trigger
AFTER INSERT ON sortie
FOR EACH ROW
EXECUTE FUNCTION mouvement_sortant();



create or replace view v_detail_sortie as 
select sortie.*,
entrant.date_entrant,
p.* 
from sortie
join entrant on entrant.id_entrant = sortie.entrant_id
join v_produit p on p.id_produit = entrant.produit_id;





---------------------------------------------------- facture -----------------------------------------------------------------

CREATE SEQUENCE facture_sequence START 1;

create table Facture(
    id_facture serial primary key,
    numero_facture varchar(100) not null unique,
    numero_commande varchar(200),
    quantite double precision,
    prix_uniatire double precision,
    tax double precision,
    produit_id int REFERENCES produit(id_produit),
    group_id int ,
    unite varchar(20),
    id_fournisseur int REFERENCES fournisseur(id_fournisseur),
    date_facture date default now()
);

CREATE OR REPLACE FUNCTION generate_numero_facture()
RETURNS TRIGGER AS $$
BEGIN
    NEW.numero_facture := 'FACTURE_' || LPAD(CAST(nextval('facture_sequence') AS VARCHAR), 4, '0');
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER before_insert_bon_facture
BEFORE INSERT ON facture
FOR EACH ROW
EXECUTE FUNCTION generate_numero_facture();



CREATE VIEW v_facture AS
SELECT 
    f.id_facture,
    f.numero_facture,
    f.numero_commande,
    f.quantite,
    f.prix_uniatire,
    f.tax,
    f.produit_id,
    p.libelle AS libelle_produit,
    p.categorie_id,
    cm.libelle AS libelle_categorie,
    p.unite_id,
    u.libelle AS libelle_unite,
    f.group_id,
    f.unite,
    f.id_fournisseur,
    fr.libelle AS libelle_fournisseur,
    fr.adress AS adresse_fournisseur,
    fr.phone AS telephone_fournisseur,
    fr.email AS email_fournisseur,
    f.date_facture
FROM Facture f
JOIN produit p ON f.produit_id = p.id_produit
JOIN fournisseur fr ON f.id_fournisseur = fr.id_fournisseur
JOIN categorie_materiel cm ON p.categorie_id = cm.id_categorie
JOIN unite u ON p.unite_id = u.id_unite;


create or replace view v_chart as
select sum(quantite_total) as quantite ,libelle, materiel, 
    categorie_id 
from v_sortie_valider 
group by materiel, categorie_id, libelle ;