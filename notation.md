# Documentation notations/rendus (License MIT, [Ip1dev/hack.course](https://github.com/lp1dev/hack.courses)
&nbsp;1
    Merci de lire attentivement ce document avant d'effectuer votre rendu. 
    Il contient des informations sur la manière dont j'effectue mes notations!
    Dans le cas où vous ne respecteriez pas les consignes indiquées ci-dessous, 
    vous vous exposez à une baisse de votre note, voire à un 0.

## I - Qu'est ce qu'il faut rendre ?

> Les règles de base qui s'appliquent aux rendus de TDs et de projets.
Elles sont relatives à ma manière de noter et de tester les rendus.

#### 1 - Du CODE ORIGINAL

C'est de loin la règle la plus importante pour moi et pourtant elle est surprenemment peu respectée :
Le code que vous me rendez doit être **LE VOTRE** (oui, oui ça semble évident dit comme ça).

---
**ATTENTION** : 
Si deux groupes me rendent **le même projet**, 
*à moins qu'on en ait discuté ensemble et que j'aie donné mon accord*, **les deux groupes auront 0/20**.
Dans les étapes de correction je n'ai ni le temps, ni l'envie de faire un travail d'investigation pour
savoir comment la triche s'est organisée. Ne donnez pas votre code à un autre groupe.

Dans le cas où un autre groupe utilise votre code **sans votre permission**, notifiez moi de la situation !

---
#### Comment considérer que du code est "votre code" ?

> Il y aura forcément des similarités entre deux projets de deux groupes différents, comment différencier une aide d'un autre groupe, une inspiration d'une source commune et du plagiat ?

J'utilise des outils d'analyse de code pour déterminer un pourçentage de similarité caractère par caractère entre les rendus.
Je considère qu'au delà de 90% de similarité (ce qui est déjà beaucoup) entre du code significatif de deux projets, les deux projets ne sont pas originaux.

> **Note**: Je m'intéresse souvent plus à la qualité du code rendu qu'au résultat et je sais reconnaître du code copié/collé avec des noms de variables changés. Si vous voulez tricher faites-le intelligement.

#### 2 - Du code qui fonctionne
Alors oui, ça aussi ça semble évident mais faites très attention à vos rendus !
C'est une question de rigueur, faites s'il vous plait l'effort de tester le projet que vous rendez avant de me l'envoyer.

Dans le cas où un projet rendu n'est pas fonctionnel, je lirai le code, si celui-ci est propre et intéressant vous aurez quelques points mais je ne pourrai pas vous donner la majorité des points d'une partie si elle ne fonctionne pas !

Ça peut sembler injuste, mais il faut impérativement que vous preniez l'habitude de bien tester ce que vous faites. 
Dans votre quotidien professionnel, ce que vos clients/collègues/collaborateurs prendront en compte c'est ce qu'ils peuvent voir et tester, pas ce qui est implémenté mais pas fonctionnel.

> **Recommandation :** Avant de faire un rendu, re-testez le projet en l'extrayant dans un autre terminal et un autre répertoire que celui dans lequel vous travailliez, vous remarquerez rapidement les dépendances, fichiers et variables d'environnement manquants.

### 3 - Du code propre  
En effet, du code qui marche c'est une chose, du code propre en est une autre. Pensez a indenté votre code, ne gardé pas des pavé de code commenter comme bloc notes, respecté les rêgle de CodeStyle..  

Votre projet devra intégré une CI minimal pour permettre de validé ce niveau de qualité. CircleCI vous y aidera. Sur le `readme.md` se trouve le Badge Circle. Si votre rendu n'est pas au vert et les rêgle de CI configurer, le TP sera noté sur la moitié des point. 

### 4 - Si vous voulez, des commentaires

Si vous n'arrivez pas à faire quelque chose mais que vous avez pourtant une idée qui vous semble intéressante n'hésitez pas à laisser quelques commentaires sous la forme que vous le souhaitez (pseudo-code, code commenté, langage humain), si je vois que vous avez une solution correcte avec une implémentation erronée cela peut jouer en votre faveur sur la notation !

# II - Comment faire son rendu ?

Pour faire le rendu de vos TDs ou de votre projet, créez une **pr** sur ce dépot GitHub (via un fork) :

## Si vous travaillez seul

> prenom_nometudiant

Par exemple : **allan_amsellem**

## Si vous travaillez en groupe

> nometudiant1_nometudiant2_nometudiant3


Une fois que vous avez vérifié que votre projet est fonctionnels,Ajouter un commentaire sur votre PR avec un "@alborq, c'est prêt !" 

Et voilà !
