# PROJET TRAMES GPS


## 1° LES ACCÈS

Le projet peut être accessible via 2 IP :  
    - __192.168.65.186__, l'adresse sur laquelle vous pouvez accéder au site.  
    - __192.168.64.157__, l'adresse sur laquelle vous pouvez accéder à la BDD.


-----------------


## 2° LA BASE DE DONNÉES

Actuellement, la BDD contient 1 base. Vous pouvez y accéder grâce au couple d'identifiants root/root.

BDD     	

      └── user  
        ├── id : int (clé primaire)  
        ├── pseudo : varchar (255)  
        └── MDP : varchar (255)  
        └── admin : tinynint (1)  

      └── GPS    
        ├── id : int (clé primaire)  
        ├── Longitute : varchar (100)    
        └── Latitude : varchar (100)    
        └── Heure : varchar (100)   

Pour fonctionner, un utilisateur doit être ajouté à la base avec le nom "dudule", le password "root", le nom
d'hôte à "%" et lui accorder tous les privilèges.

Un export de cette base existe dans le fichier __./bdd/BDD.sql__, que vous pouvez importer directement dans PhpMyAdmin.  
Tout est déjà configuré, est un Admin existe sous le nom de 'admin' avec pour mot de passe 'nimba'.  

-----------------


## 3° ORGANISATION DU CODE
 
* __./bdd__    
    *BASE.sql* -> un export clean de la base afin que vous puissiez l'importer dans PhpMyAdmin  

* __./documentation__  
    *cahier_des_charges.docx* -> document qui contient le cahier des charges   
    *GANTT.xlsx* -> document GANTT du projet  
    *presentation.pttx* -> présentation du pojet sur PowerPoint  
    *recettage_tests.docx* -> document listant les fonctionnalités et contenant les tests réalisés pour le projet  
    *TP2_CommunicationSerie.pdf* -> Rappel du projet avec le fichier pdf

* __./QtServer__  
    __/QTpartie2__  
        *- database.cpp* -> fichier permetant la connexion à la BDD et l'envoi des trames  
        *- main.cpp* -> fichier de base pour Qt  
        *- Qtpartie2.vcxproj*  
        *- Qtpartie2.vcxproj.filters*  
        *- Qtpartie2.vcxproj.user*  
        *- serialreader.cpp* -> fichier utilisé pour la réception et le décodage des trames
        *- serialreader.h* -> déclaration de la classe et des méthodes  
    *Qtpartie2.sln* -> fichier qui vous permet d'ouvrir directement tout le projet  
        

*readme.md* -> ce même fichier que vous êtes en train de lire pour vous aider à comprendre le code  

-----------------


Pour toutes questions sur le projet (en ce qui concerne la première partie), n'hésitez pas à venir demander à Tom, Junior, Alexandre ou Éloïse pour plus d'informations.  

Enjoy ! 
