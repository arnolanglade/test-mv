Test
====
La première chose avant un refactoring est de mettre en place un harnais de tests si cette partie de code n’est pas testée. Malheureusement, je n’ai pas accès à toute la codebase ce qui rend la mise en place du harnais quasiment impossible. Comme je n'ai pas de personne du métier ou de dév, je vais devoir prendre des décisions à leur place (peut être qu’elles ne correspondront pas à la réalité).

N”hésitez pas à regarder les messages de commit pour plus d’informations.

Je prends le parti de m’arrêter ici car il est difficile de refactorer pour refactorer (et je n’ai pas accès à la codebase/PM/dev). En général, un gros refactoring est tiré par à un besoin et ce besoin permet de mettre des bornes. Ici, on a plutôt fait du “boy scoutisme”, on a rendu le code plus clair mais sans faire de changements majeurs. S’il passe les tests il peut partir en prod. On n’a pas un code SOLID mais plus compréhensible (on code pour des humains pas des machines). Ici si on laisse cet objet en l’état, il risque de se transformer en god object (objet énorme qui fait tout). Le mieux serait de créer un objet par use case, par la suite pourrait permettre d’utiliser le pattern command/commandHandler. 

Cette application semble fonctionner avec des modèles anémiques. Si cette application est réellement orientée métier, il faudrait réfléchir à les transformer en modèle riche (mais ça peut être réellement compliqué). On peut voir aussi que que les agrégats sont liés par références (sûrement à cause de Doctrine). Il faudrait plutôt utiliser des liaisons par ID ce qui rendraient les aggregat indépendants.

En espérant pouvoir en discuter de vive voix.
