create table departement(
    id_departement serial primary key,
    libelle varchar(200),
    email varchar(100),
    mot_passe varchar(100)
);

INSERT INTO departement (libelle , email , mot_passe)
VALUES 
    ('Departement Ventes' , 'vente@gmail.com' , 'vente'),
    ('Departement Marketing et Communication' , 'mc@gmail.com' , 'mc'),
    ('Departement informatique' , 'informatioque@gmail.com' , 'info'),
    ('Departement RH' , 'rh@gmail.com' , 'rh');


    
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


drop view v_facture;
drop table facture;
drop sequence facture_sequence;