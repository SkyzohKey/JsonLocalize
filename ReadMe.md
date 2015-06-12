[![Join the chat at https://gitter.im/SkyzohKeyx/JsonLocalize](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/SkyzohKeyx/JsonLocalize?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
`English readme coming soon!`

Bonjour à toutes et à tous,
J'ai aujourd'hui l'honneur de vous présenter une nouvelle classe PHP que vous allez, je le sent dans vos regards pleins d'étoiles, adorer !

# **๖ۣۜIntroduction -**
**JsonLocalizer** est une *classe PHP* que j'ai développé afin de **pouvoir traduire rapidement** et **sans avoir a utiliser de gros framework** *(gettext, i18n, localize, etc...)*. Mon idée était également de pouvoir **simplement** écrire le **chemin vers la valeur du fichier JSON** directement dans notre vue *(ou page selon l'architecture choisie)*. J'ai donc **longuement réfléchis**, **fais des croquis et des tests** afin de trouver la meilleure façon de faire, tout en **respectant une vraie syntaxe JSON**. Après 3 jours de **développement intensif** - d'optimisations **massives** et de **peaufinage du code**, j'en suis donc arrivé à une **version bêta** que je souhaiterais aujourd'hui partager avec vous.

(Surtout que ***Krizzy*** en avait besoin et que *j'adore partager gratuitement et librement* mes projets ^^).

# **๖ۣۜExemples -**
## - Inclure la classe et instancier l'objet :

    $parser = new JsonLocalizer("./langs/", "json", "fr");

## - Récupérer la langue dans une session et rendre la page :

    session_start();
    $lang = (isset($_SESSION['lang'])) ? $_SESSION['lang'] : 'fr'; // Si la session 'lang' existe alors on l'utilise, sinon on utilise la langue 'fr'.
    $parser->setLang($lang);
    $parser->render('./pages/home.php');

## - Rendre un texte au lieu d'un fichier :

    $parser->render(null, '<span class="hint">{home.fields.username.hint}</span>');[/php]

## - Rendre plusieurs pages/vues avec le chaining :

    php$parser->render('./pages/home.php');
           ->render('./theme/default/menu.php')
           ->render('./pages/home.php')
           ->render('./theme/default/footer.php');

## - Fichier langue basique :

    // File: ./langs/lang-fr.json
    {
        "_lang":
        {
            "author": "SkyzohKey",
            "version": "0.0.1",
            "flag": "fr",
            "country": "France",
            "state": "0%"
        },

        "website":
        {
            "title": "Mon super site multi-langue !",

            "home":
            {
                "fields":
                {
                    "username":
                    {
                        "label": "Nom du compte",
                        "hint": "Le nom de compte est votre identifiant, il définit sous quel pseudo vous apparaissez en postant un message.",
                        "errors":
                        {
                            "blank": "Vous devez compléter le champ \"Nom du compte\" !",
                            "length": "La longueur de votre Nom de compte doit être comprise entre 3 et 12 caractères.",
                            "alreadyUsed": "Ce nom de compte est déjà utilisé, merci d'en choisir un autre ou de vous connecter."
                        }
                    },
                    "password":
                    {
                        "label": "Mot de passe",
                        "hint": "Doit être comprit entre 8 et 666 caractères."
                    },
                    "mailaddress":
                    {
                        "label": "Adresse mail",
                        "hint": "Votre adresse mail vous permettra de changer votre mot de passe en cas d'oublis."
                    }
                }
            }
        }
    }

# **๖ۣۜLiens utiles -**

*  [Repository GitHub](https://github.com/SkyzohKeyx/JsonLocalize/)
*  [Signaler un bug, ou une faille](https://github.com/SkyzohKeyx/JsonLocalize/issues/)
*  [Envie de contribuer ?](https://github.com/SkyzohKeyx/JsonLocalize/fork/)
