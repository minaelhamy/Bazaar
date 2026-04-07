@extends('admin.layout.default')
@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.add_new') }}</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item text-dark"><a
                        href="{{ URL::to('admin/language-settings') }}" class="color-changer">{{ trans('labels.languages') }}</a></li>
                <li class="breadcrumb-item active {{ session()->get('direction') == 2 ? 'breadcrumb-rtl' : '' }}"
                    aria-current="page">{{ trans('labels.add') }}</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 my-3 box-shadow">
                <div class="card-body">
                    <form method="post" action="{{ URL::to('admin/language-settings/store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-sm-3 col-md-12">
                                <div class="form-group mb-3">
                                    <input type="hidden" name="name" id="language">
                                    <label for="language" class="col-form-label">{{ trans('labels.languages') }} <span
                                            class="text-danger"> *
                                        </span></label>
                                    <select name="code" class="form-select code-dropdown" id="code" required>
                                        <option value="" selected>{{ trans('labels.select') }}</option>
                                        <option value="ab"{{ old('code') == 'ab' ? 'selected' : '' }}
                                            data-language-name="аҧсуа">Abkhaz - ab (аҧсуа)</option>
                                        <option value="aa"{{ old('code') == 'aa' ? 'selected' : '' }}
                                            data-language-name="Afaraf">Afar - aa (Afaraf)</option>
                                        <option value="af"{{ old('code') == 'af' ? 'selected' : '' }}
                                            data-language-name="Afrikaans">Afrikaans - af (Afrikaans)</option>
                                        <option value="ak"{{ old('code') == 'ak' ? 'selected' : '' }}
                                            data-language-name="Akan">Akan - ak (Akan)</option>
                                        <option value="sq"{{ old('code') == 'sq' ? 'selected' : '' }}
                                            data-language-name="Shqip">Albanian - sq (Shqip)</option>
                                        <option value="am"{{ old('code') == 'am' ? 'selected' : '' }}
                                            data-language-name="አማርኛ">Amharic - am (አማርኛ)</option>
                                        <option value="ar"{{ old('code') == 'ar' ? 'selected' : '' }}
                                            data-language-name="العربية">Arabic - ar (العربية)</option>
                                        <option value="an"{{ old('code') == 'an' ? 'selected' : '' }}
                                            data-language-name="Aragonés">Aragonese - an (Aragonés)</option>
                                        <option value="hy"{{ old('code') == 'hy' ? 'selected' : '' }}
                                            data-language-name="Հայերեն">Armenian - hy (Հայերեն)</option>
                                        <option value="as"{{ old('code') == 'as' ? 'selected' : '' }}
                                            data-language-name="অসমীয়া">Assamese - as (অসমীয়া)</option>
                                        <option value="av"{{ old('code') == 'av' ? 'selected' : '' }}
                                            data-language-name="авар мацӀ, магӀарул мацӀ">Avaric - av (авар мацӀ, магӀарул
                                            мацӀ)</option>
                                        <option value="ae"{{ old('code') == 'ae' ? 'selected' : '' }}
                                            data-language-name="avesta">Avestan - ae (avesta)</option>
                                        <option value="ay"{{ old('code') == 'ay' ? 'selected' : '' }}
                                            data-language-name="aymar aru">Aymara - ay (aymar aru)</option>
                                        <option value="az"{{ old('code') == 'az' ? 'selected' : '' }}
                                            data-language-name="azərbaycan dili">Azerbaijani - az (azərbaycan dili)</option>
                                        <option value="bm"{{ old('code') == 'bm' ? 'selected' : '' }}
                                            data-language-name="bamanankan">Bambara - bm (bamanankan)</option>
                                        <option value="ba"{{ old('code') == 'ba' ? 'selected' : '' }}
                                            data-language-name="башҡорт теле">Bashkir - ba (башҡорт теле)</option>
                                        <option value="eu"{{ old('code') == 'eu' ? 'selected' : '' }}
                                            data-language-name="euskara, euskera">Basque - eu (euskara, euskera)</option>
                                        <option value="be"{{ old('code') == 'be' ? 'selected' : '' }}
                                            data-language-name="Беларуская">Belarusian - be (Беларуская)</option>
                                        <option value="bn"{{ old('code') == 'bn' ? 'selected' : '' }}
                                            data-language-name="বাংলা">Bengali - bn (বাংলা)</option>
                                        <option value="bh"{{ old('code') == 'bh' ? 'selected' : '' }}
                                            data-language-name="भोजपुरी">Bihari - bh (भोजपुरी)</option>
                                        <option value="bi"{{ old('code') == 'bi' ? 'selected' : '' }}
                                            data-language-name="Bislama">Bislama - bi (Bislama)</option>
                                        <option value="bs"{{ old('code') == 'bs' ? 'selected' : '' }}
                                            data-language-name="bosanski jezik">Bosnian - bs (bosanski jezik)</option>
                                        <option value="br"{{ old('code') == 'br' ? 'selected' : '' }}
                                            data-language-name="brezhoneg">Breton - br (brezhoneg)</option>
                                        <option value="bg"{{ old('code') == 'bg' ? 'selected' : '' }}
                                            data-language-name="български език">Bulgarian - bg (български език)</option>
                                        <option value="my"{{ old('code') == 'my' ? 'selected' : '' }}
                                            data-language-name="ဗမာစာ">Burmese - my (ဗမာစာ)</option>
                                        <option value="ca"{{ old('code') == 'ca' ? 'selected' : '' }}
                                            data-language-name="Català">Catalan; Valencian - ca (Català)</option>
                                        <option value="ch"{{ old('code') == 'ch' ? 'selected' : '' }}
                                            data-language-name="Chamoru">Chamorro - ch (Chamoru)</option>
                                        <option value="ce"{{ old('code') == 'ce' ? 'selected' : '' }}
                                            data-language-name="нохчийн мотт">Chechen - ce (нохчийн мотт)</option>
                                        <option value="ny"{{ old('code') == 'ny' ? 'selected' : '' }}
                                            data-language-name="chiCheŵa, chinyanja">Chichewa; Chewa; Nyanja - ny (chiCheŵa,
                                            chinyanja)</option>
                                        <option value="zh"{{ old('code') == 'zh' ? 'selected' : '' }}
                                            data-language-name="中文 (Zhōngwén), 汉语, 漢語">Chinese - zh (中文 (Zhōngwén), 汉语, 漢語)
                                        </option>
                                        <option value="cv"{{ old('code') == 'cv' ? 'selected' : '' }}
                                            data-language-name="чӑваш чӗлхи">Chuvash - cv (чӑваш чӗлхи)</option>
                                        <option value="kw"{{ old('code') == 'kw' ? 'selected' : '' }}
                                            data-language-name="Kernewek">Cornish - kw (Kernewek)</option>
                                        <option value="co"{{ old('code') == 'co' ? 'selected' : '' }}
                                            data-language-name="corsu, lingua corsa">Corsican - co (corsu, lingua corsa)
                                        </option>
                                        <option value="cr"{{ old('code') == 'cr' ? 'selected' : '' }}
                                            data-language-name="ᓀᐦᐃᔭᐍᐏᐣ">Cree - cr (ᓀᐦᐃᔭᐍᐏᐣ)</option>
                                        <option value="hr"{{ old('code') == 'hr' ? 'selected' : '' }}
                                            data-language-name="hrvatski">Croatian - hr (hrvatski)</option>
                                        <option value="cs"{{ old('code') == 'cs' ? 'selected' : '' }}
                                            data-language-name="česky, čeština">Czech - cs (česky, čeština)</option>
                                        <option value="da"{{ old('code') == 'da' ? 'selected' : '' }}
                                            data-language-name="dansk">Danish - da (dansk)</option>
                                        <option value="dv"{{ old('code') == 'dv' ? 'selected' : '' }}
                                            data-language-name="ދިވެހި">Divehi; Dhivehi; Maldivian; - dv (ދިވެހި)</option>
                                        <option value="nl"{{ old('code') == 'nl' ? 'selected' : '' }}
                                            data-language-name="Nederlands, Vlaams">Dutch - nl (Nederlands, Vlaams)
                                        </option>
                                        <option value="en"{{ old('code') == 'en' ? 'selected' : '' }}
                                            data-language-name="English">English - en (English)</option>
                                        <option value="eo"{{ old('code') == 'eo' ? 'selected' : '' }}
                                            data-language-name="Esperanto">Esperanto - eo (Esperanto)</option>
                                        <option value="et"{{ old('code') == 'et' ? 'selected' : '' }}
                                            data-language-name="eesti, eesti keel">Estonian - et (eesti, eesti keel)
                                        </option>
                                        <option value="ee"{{ old('code') == 'ee' ? 'selected' : '' }}
                                            data-language-name="Eʋegbe">Ewe - ee (Eʋegbe)</option>
                                        <option value="fo"{{ old('code') == 'fo' ? 'selected' : '' }}
                                            data-language-name="føroyskt">Faroese - fo (føroyskt)</option>
                                        <option value="fj"{{ old('code') == 'fj' ? 'selected' : '' }}
                                            data-language-name="vosa Vakaviti">Fijian - fj (vosa Vakaviti)</option>
                                        <option value="fi"{{ old('code') == 'fi' ? 'selected' : '' }}
                                            data-language-name="suomi, suomen kieli">Finnish - fi (suomi, suomen kieli)
                                        </option>
                                        <option value="fr"{{ old('code') == 'fr' ? 'selected' : '' }}
                                            data-language-name="français, langue française">French - fr (français, langue
                                            française)</option>
                                        <option value="ff"{{ old('code') == 'ff' ? 'selected' : '' }}
                                            data-language-name="Fulfulde, Pulaar, Pular">Fula; Fulah; Pulaar; Pular - ff
                                            (Fulfulde, Pulaar, Pular)</option>
                                        <option value="gl"{{ old('code') == 'gl' ? 'selected' : '' }}
                                            data-language-name="Galego">Galician - gl (Galego)</option>
                                        <option value="ka"{{ old('code') == 'ka' ? 'selected' : '' }}
                                            data-language-name="ქართული">Georgian - ka (ქართული)</option>
                                        <option value="de"{{ old('code') == 'de' ? 'selected' : '' }}
                                            data-language-name="Deutsch">German - de (Deutsch)</option>
                                        <option value="el"{{ old('code') == 'el' ? 'selected' : '' }}
                                            data-language-name="Ελληνικά">Greek, Modern - el (Ελληνικά)</option>
                                        <option value="gn"{{ old('code') == 'gn' ? 'selected' : '' }}
                                            data-language-name="Avañeẽ">Guaraní - gn (Avañeẽ)</option>
                                        <option value="gu"{{ old('code') == 'gu' ? 'selected' : '' }}
                                            data-language-name="ગુજરાતી">Gujarati - gu (ગુજરાતી)</option>
                                        <option value="ht"{{ old('code') == 'ht' ? 'selected' : '' }}
                                            data-language-name="Kreyòl ayisyen">Haitian; Haitian Creole - ht (Kreyòl
                                            ayisyen)</option>
                                        <option value="ha"{{ old('code') == 'ha' ? 'selected' : '' }}
                                            data-language-name="Hausa, هَوُسَ">Hausa - ha (Hausa, هَوُسَ)</option>
                                        <option value="he"{{ old('code') == 'he' ? 'selected' : '' }}
                                            data-language-name="modern">Hebrew (modern) - he (עברית)</option>
                                        <option value="hz"{{ old('code') == 'hz' ? 'selected' : '' }}
                                            data-language-name="Otjiherero">Herero - hz (Otjiherero)</option>
                                        <option value="hi"{{ old('code') == 'hi' ? 'selected' : '' }}
                                            data-language-name="Hindi">Hindi - hi (हिन्दी, हिंदी)</option>
                                        <option value="ho"{{ old('code') == 'ho' ? 'selected' : '' }}
                                            data-language-name="हिन्दी, हिंदी">Hiri Motu - ho (Hiri Motu)</option>
                                        <option value="hu"{{ old('code') == 'hu' ? 'selected' : '' }}
                                            data-language-name="Magyar">Hungarian - hu (Magyar)</option>
                                        <option value="ia"{{ old('code') == 'ia' ? 'selected' : '' }}
                                            data-language-name="Interlingua">Interlingua - ia (Interlingua)</option>
                                        <option value="id"{{ old('code') == 'id' ? 'selected' : '' }}
                                            data-language-name="Bahasa Indonesia">Indonesian - id (Bahasa Indonesia)
                                        </option>
                                        <option value="ie"{{ old('code') == 'ie' ? 'selected' : '' }}
                                            data-language-name="Originally called Occidental; then Interlingue after WWII">
                                            Interlingue - ie (Originally called Occidental; then Interlingue after WWII)
                                        </option>
                                        <option value="ga"{{ old('code') == 'ga' ? 'selected' : '' }}
                                            data-language-name="Gaeilge">Irish - ga (Gaeilge)</option>
                                        <option value="ig"{{ old('code') == 'ig' ? 'selected' : '' }}
                                            data-language-name="Asụsụ Igbo">Igbo - ig (Asụsụ Igbo)</option>
                                        <option value="ik"{{ old('code') == 'ik' ? 'selected' : '' }}
                                            data-language-name="Iñupiaq, Iñupiatun">Inupiaq - ik (Iñupiaq, Iñupiatun)
                                        </option>
                                        <option value="io"{{ old('code') == 'io' ? 'selected' : '' }}
                                            data-language-name="Ido">Ido - io (Ido)</option>
                                        <option value="is"{{ old('code') == 'is' ? 'selected' : '' }}
                                            data-language-name="Íslenska">Icelandic - is (Íslenska)</option>
                                        <option value="it"{{ old('code') == 'it' ? 'selected' : '' }}
                                            data-language-name="Italiano">Italian - it (Italiano)</option>
                                        <option value="iu"{{ old('code') == 'iu' ? 'selected' : '' }}
                                            data-language-name="ᐃᓄᒃᑎᑐᑦ">Inuktitut - iu (ᐃᓄᒃᑎᑐᑦ)</option>
                                        <option value="ja"{{ old('code') == 'ja' ? 'selected' : '' }}
                                            data-language-name="日本語 (にほんご／にっぽんご)">Japanese - ja (日本語 (にほんご／にっぽんご))</option>
                                        <option value="jv"{{ old('code') == 'jv' ? 'selected' : '' }}
                                            data-language-name="basa Jawa">Javanese - jv (basa Jawa)</option>
                                        <option value="kl"{{ old('code') == 'kl' ? 'selected' : '' }}
                                            data-language-name="kalaallisut, kalaallit oqaasii">Kalaallisut, Greenlandic -
                                            kl (kalaallisut, kalaallit oqaasii)</option>
                                        <option value="kn"{{ old('code') == 'kn' ? 'selected' : '' }}
                                            data-language-name="ಕನ್ನಡ">Kannada - kn (ಕನ್ನಡ)</option>
                                        <option value="kr"{{ old('code') == 'kr' ? 'selected' : '' }}
                                            data-language-name="Kanuri">Kanuri - kr (Kanuri)</option>
                                        <option value="ks"{{ old('code') == 'ks' ? 'selected' : '' }}
                                            data-language-name="कश्मीरी, كشميري">Kashmiri - ks (कश्मीरी, كشميري‎)</option>
                                        <option value="kk"{{ old('code') == 'kk' ? 'selected' : '' }}
                                            data-language-name="Қазақ тілі">Kazakh - kk (Қазақ тілі)</option>
                                        <option value="km"{{ old('code') == 'km' ? 'selected' : '' }}
                                            data-language-name="ភាសាខ្មែរ">Khmer - km (ភាសាខ្មែរ)</option>
                                        <option value="ki"{{ old('code') == 'ki' ? 'selected' : '' }}
                                            data-language-name="Gĩkũyũ">Kikuyu, Gikuyu - ki (Gĩkũyũ)</option>
                                        <option value="rw"{{ old('code') == 'rw' ? 'selected' : '' }}
                                            data-language-name="Ikinyarwanda">Kinyarwanda - rw (Ikinyarwanda)</option>
                                        <option value="ky"{{ old('code') == 'ky' ? 'selected' : '' }}
                                            data-language-name="кыргыз тили">Kirghiz, Kyrgyz - ky (кыргыз тили)</option>
                                        <option value="kv"{{ old('code') == 'kv' ? 'selected' : '' }}
                                            data-language-name="коми кыв">Komi - kv (коми кыв)</option>
                                        <option value="kg"{{ old('code') == 'kg' ? 'selected' : '' }}
                                            data-language-name="KiKongo">Kongo - kg (KiKongo)</option>
                                        <option value="ko"{{ old('code') == 'ko' ? 'selected' : '' }}
                                            data-language-name="한국어 (韓國語), 조선말 (朝鮮語)">Korean - ko (한국어 (韓國語), 조선말 (朝鮮語))
                                        </option>
                                        <option value="ku"{{ old('code') == 'ku' ? 'selected' : '' }}
                                            data-language-name="Kurdî, كوردی">Kurdish - ku (Kurdî, كوردی‎)</option>
                                        <option value="kj"{{ old('code') == 'kj' ? 'selected' : '' }}
                                            data-language-name="Kuanyama">Kwanyama, Kuanyama - kj (Kuanyama)</option>
                                        <option value="la"{{ old('code') == 'la' ? 'selected' : '' }}
                                            data-language-name="latine, lingua latina">Latin - la (latine, lingua latina)
                                        </option>
                                        <option value="lb"{{ old('code') == 'lb' ? 'selected' : '' }}
                                            data-language-name="Lëtzebuergesch">Luxembourgish, Letzeburgesch - lb
                                            (Lëtzebuergesch)</option>
                                        <option value="lg"{{ old('code') == 'lg' ? 'selected' : '' }}
                                            data-language-name="Luganda">Luganda - lg (Luganda)</option>
                                        <option value="li"{{ old('code') == 'li' ? 'selected' : '' }}
                                            data-language-name="Limburgs">Limburgish, Limburgan, Limburger - li (Limburgs)
                                        </option>
                                        <option value="ln"{{ old('code') == 'ln' ? 'selected' : '' }}
                                            data-language-name="Lingála">Lingala - ln (Lingála)</option>
                                        <option value="lo"{{ old('code') == 'lo' ? 'selected' : '' }}
                                            data-language-name="ພາສາລາວ">Lao - lo (ພາສາລາວ)</option>
                                        <option value="lt"{{ old('code') == 'lt' ? 'selected' : '' }}
                                            data-language-name="lietuvių kalba">Lithuanian - lt (lietuvių kalba)</option>
                                        <option value="lu"{{ old('code') == 'lu' ? 'selected' : '' }}
                                            data-language-name='"nativeName":""'>Luba-Katanga - lu ( "nativeName":"")
                                        </option>
                                        <option value="lv"{{ old('code') == 'lv' ? 'selected' : '' }}
                                            data-language-name="latviešu valoda">Latvian - lv (latviešu valoda)</option>
                                        <option value="gv"{{ old('code') == 'gv' ? 'selected' : '' }}
                                            data-language-name="Gaelg, Gailck">Manx - gv (Gaelg, Gailck)</option>
                                        <option value="mk"{{ old('code') == 'mk' ? 'selected' : '' }}
                                            data-language-name="македонски јазик">Macedonian - mk (македонски јазик)
                                        </option>
                                        <option value="mg"{{ old('code') == 'mg' ? 'selected' : '' }}
                                            data-language-name="Malagasy fiteny">Malagasy - mg (Malagasy fiteny)</option>
                                        <option value="ms"{{ old('code') == 'ms' ? 'selected' : '' }}
                                            data-language-name="bahasa Melayu, بهاس ملايو">Malay - ms (bahasa Melayu, بهاس
                                            ملايو‎)</option>
                                        <option value="ml"{{ old('code') == 'ml' ? 'selected' : '' }}
                                            data-language-name="മലയാളം">Malayalam - ml (മലയാളം)</option>
                                        <option value="mt"{{ old('code') == 'mt' ? 'selected' : '' }}
                                            data-language-name="Maltese">Maltese - mt (Malti)</option>
                                        <option value="mi"{{ old('code') == 'mi' ? 'selected' : '' }}
                                            data-language-name="Malti">Māori - mi (te reo Māori)</option>
                                        <option value="mr"{{ old('code') == 'mr' ? 'selected' : '' }}
                                            data-language-name="Marāṭhī">Marathi (Marāṭhī) - mr (मराठी)</option>
                                        <option value="mh"{{ old('code') == 'mh' ? 'selected' : '' }}
                                            data-language-name="Kajin M̧ajeļ">Marshallese - mh (Kajin M̧ajeļ)</option>
                                        <option value="mn"{{ old('code') == 'mn' ? 'selected' : '' }}
                                            data-language-name="монгол">Mongolian - mn (монгол)</option>
                                        <option value="na"{{ old('code') == 'na' ? 'selected' : '' }}
                                            data-language-name="Ekakairũ Naoero">Nauru - na (Ekakairũ Naoero)</option>
                                        <option value="nv"{{ old('code') == 'nv' ? 'selected' : '' }}
                                            data-language-name="Diné bizaad, Dinékʼehǰí">Navajo, Navaho - nv (Diné bizaad,
                                            Dinékʼehǰí)</option>
                                        <option value="nb"{{ old('code') == 'nb' ? 'selected' : '' }}
                                            data-language-name="Norsk bokmål">Norwegian Bokmål - nb (Norsk bokmål)</option>
                                        <option value="nd"{{ old('code') == 'nd' ? 'selected' : '' }}
                                            data-language-name="isiNdebele">North Ndebele - nd (isiNdebele)</option>
                                        <option value="ne"{{ old('code') == 'ne' ? 'selected' : '' }}
                                            data-language-name="नेपाली">Nepali - ne (नेपाली)</option>
                                        <option value="ng"{{ old('code') == 'ng' ? 'selected' : '' }}
                                            data-language-name="Owambo">Ndonga - ng (Owambo)</option>
                                        <option value="nn"{{ old('code') == 'nn' ? 'selected' : '' }}
                                            data-language-name="Norsk nynorsk">Norwegian Nynorsk - nn (Norsk nynorsk)
                                        </option>
                                        <option value="no"{{ old('code') == 'no' ? 'selected' : '' }}
                                            data-language-name="Norsk">Norwegian - no (Norsk)</option>
                                        <option value="ii"{{ old('code') == 'ii' ? 'selected' : '' }}
                                            data-language-name="ꆈꌠ꒿ Nuosuhxop">Nuosu - ii (ꆈꌠ꒿ Nuosuhxop)</option>
                                        <option value="nr"{{ old('code') == 'nr' ? 'selected' : '' }}
                                            data-language-name="isiNdebele">South Ndebele - nr (isiNdebele)</option>
                                        <option value="oc"{{ old('code') == 'oc' ? 'selected' : '' }}
                                            data-language-name="Occitan">Occitan - oc (Occitan)</option>
                                        <option value="oj"{{ old('code') == 'oj' ? 'selected' : '' }}
                                            data-language-name="ᐊᓂᔑᓈᐯᒧᐎᓐ">Ojibwe, Ojibwa - oj (ᐊᓂᔑᓈᐯᒧᐎᓐ)</option>
                                        <option value="cu"{{ old('code') == 'cu' ? 'selected' : '' }}
                                            data-language-name="ѩзыкъ словѣньскъ">Old Church Slavonic, Church Slavic,
                                            Church Slavonic, Old Bulgarian, Old Slavonic - cu (ѩзыкъ словѣньскъ)</option>
                                        <option value="om"{{ old('code') == 'om' ? 'selected' : '' }}
                                            data-language-name="Afaan Oromoo">Oromo - om (Afaan Oromoo)</option>
                                        <option value="or"{{ old('code') == 'or' ? 'selected' : '' }}
                                            data-language-name="ଓଡ଼ିଆ">Oriya - or (ଓଡ଼ିଆ)</option>
                                        <option value="os"{{ old('code') == 'os' ? 'selected' : '' }}
                                            data-language-name="ирон æвзаг">Ossetian, Ossetic - os (ирон æвзаг)</option>
                                        <option value="pa"{{ old('code') == 'pa' ? 'selected' : '' }}
                                            data-language-name="ਪੰਜਾਬੀ, پنجابی">Panjabi, Punjabi - pa (ਪੰਜਾਬੀ, پنجابی‎)
                                        </option>
                                        <option value="pi"{{ old('code') == 'pi' ? 'selected' : '' }}
                                            data-language-name="पाऴि">Pāli - pi (पाऴि)</option>
                                        <option value="fa"{{ old('code') == 'fa' ? 'selected' : '' }}
                                            data-language-name="فارسی">Persian - fa (فارسی)</option>
                                        <option value="pl"{{ old('code') == 'pl' ? 'selected' : '' }}
                                            data-language-name="polski">Polish - pl (polski)</option>
                                        <option value="ps"{{ old('code') == 'ps' ? 'selected' : '' }}
                                            data-language-name="پښتو">Pashto, Pushto - ps (پښتو)</option>
                                        <option value="pt"{{ old('code') == 'pt' ? 'selected' : '' }}
                                            data-language-name="Português">Portuguese - pt (Português)</option>
                                        <option value="qu"{{ old('code') == 'qu' ? 'selected' : '' }}
                                            data-language-name="Runa Simi, Kichwa">Quechua - qu (Runa Simi, Kichwa)
                                        </option>
                                        <option value="rm"{{ old('code') == 'rm' ? 'selected' : '' }}
                                            data-language-name="rumantsch grischun">Romansh - rm (rumantsch grischun)
                                        </option>
                                        <option value="rn"{{ old('code') == 'rn' ? 'selected' : '' }}
                                            data-language-name="kiRundi">Kirundi - rn (kiRundi)</option>
                                        <option value="ro"{{ old('code') == 'ro' ? 'selected' : '' }}
                                            data-language-name="română">Romanian, Moldavian, Moldovan - ro (română)
                                        </option>
                                        <option value="ru"{{ old('code') == 'ru' ? 'selected' : '' }}
                                            data-language-name="русский язык">Russian - ru (русский язык)</option>
                                        <option value="sa"{{ old('code') == 'sa' ? 'selected' : '' }}
                                            data-language-name="संस्कृतम्">Sanskrit (Saṁskṛta) - sa (संस्कृतम्)</option>
                                        <option value="sc"{{ old('code') == 'sc' ? 'selected' : '' }}
                                            data-language-name="sardu">Sardinian - sc (sardu)</option>
                                        <option value="sd"{{ old('code') == 'sd' ? 'selected' : '' }}
                                            data-language-name="सिन्धी, سنڌي، سندھی">Sindhi - sd (सिन्धी, سنڌي، سندھی‎)
                                        </option>
                                        <option value="se"{{ old('code') == 'se' ? 'selected' : '' }}
                                            data-language-name="Davvisámegiella">Northern Sami - se (Davvisámegiella)
                                        </option>
                                        <option value="sm"{{ old('code') == 'sm' ? 'selected' : '' }}
                                            data-language-name="gagana faa Samoa">Samoan - sm (gagana faa Samoa)</option>
                                        <option value="sg"{{ old('code') == 'sg' ? 'selected' : '' }}
                                            data-language-name="yângâ tî sängö">Sango - sg (yângâ tî sängö)</option>
                                        <option value="sr"{{ old('code') == 'sr' ? 'selected' : '' }}
                                            data-language-name="српски језик">Serbian - sr (српски језик)</option>
                                        <option value="gd"{{ old('code') == 'gd' ? 'selected' : '' }}
                                            data-language-name="Gàidhlig">Scottish Gaelic; Gaelic - gd (Gàidhlig)</option>
                                        <option value="sn"{{ old('code') == 'sn' ? 'selected' : '' }}
                                            data-language-name="chiShona">Shona - sn (chiShona)</option>
                                        <option value="si"{{ old('code') == 'si' ? 'selected' : '' }}
                                            data-language-name="සිංහල">Sinhala, Sinhalese - si (සිංහල)</option>
                                        <option value="sk"{{ old('code') == 'sk' ? 'selected' : '' }}
                                            data-language-name="slovenčina">Slovak - sk (slovenčina)</option>
                                        <option value="sl"{{ old('code') == 'sl' ? 'selected' : '' }}
                                            data-language-name="slovenščina">Slovene - sl (slovenščina)</option>
                                        <option value="so"{{ old('code') == 'so' ? 'selected' : '' }}
                                            data-language-name="Soomaaliga, af Soomaali">Somali - so (Soomaaliga, af
                                            Soomaali)</option>
                                        <option value="st"{{ old('code') == 'st' ? 'selected' : '' }}
                                            data-language-name="Sesotho">Southern Sotho - st (Sesotho)</option>
                                        <option value="es"{{ old('code') == 'es' ? 'selected' : '' }}
                                            data-language-name="español, castellano">Spanish; Castilian - es (español,
                                            castellano)</option>
                                        <option value="su"{{ old('code') == 'su' ? 'selected' : '' }}
                                            data-language-name="Basa Sunda">Sundanese - su (Basa Sunda)</option>
                                        <option value="sw"{{ old('code') == 'sw' ? 'selected' : '' }}
                                            data-language-name="Kiswahili">Swahili - sw (Kiswahili)</option>
                                        <option value="ss"{{ old('code') == 'ss' ? 'selected' : '' }}
                                            data-language-name="SiSwati">Swati - ss (SiSwati)</option>
                                        <option value="sv"{{ old('code') == 'sv' ? 'selected' : '' }}
                                            data-language-name="svenska">Swedish - sv (svenska)</option>
                                        <option value="ta"{{ old('code') == 'ta' ? 'selected' : '' }}
                                            data-language-name="தமிழ்">Tamil - ta (தமிழ்)</option>
                                        <option value="te"{{ old('code') == 'te' ? 'selected' : '' }}
                                            data-language-name="తెలుగు">Telugu - te (తెలుగు)</option>
                                        <option value="tg"{{ old('code') == 'tg' ? 'selected' : '' }}
                                            data-language-name="тоҷикӣ, toğikī, تاجیکی">Tajik - tg (тоҷикӣ, toğikī,
                                            تاجیکی‎)</option>
                                        <option value="th"{{ old('code') == 'th' ? 'selected' : '' }}
                                            data-language-name="ไทย">Thai - th (ไทย)</option>
                                        <option value="ti"{{ old('code') == 'ti' ? 'selected' : '' }}
                                            data-language-name="ትግርኛ">Tigrinya - ti (ትግርኛ)</option>
                                        <option value="bo"{{ old('code') == 'bo' ? 'selected' : '' }}
                                            data-language-name="བོད་ཡིག">Tibetan Standard, Tibetan, Central - bo (བོད་ཡིག)
                                        </option>
                                        <option value="tk"{{ old('code') == 'tk' ? 'selected' : '' }}
                                            data-language-name="Türkmen, Түркмен">Turkmen - tk (Türkmen, Түркмен)</option>
                                        <option value="tl"{{ old('code') == 'tl' ? 'selected' : '' }}
                                            data-language-name="Wikang Tagalog, ᜏᜒᜃᜅ᜔ ᜆᜄᜎᜓᜄ᜔">Tagalog - tl (Wikang Tagalog,
                                            ᜏᜒᜃᜅ᜔ ᜆᜄᜎᜓᜄ᜔)</option>
                                        <option value="tn"{{ old('code') == 'tn' ? 'selected' : '' }}
                                            data-language-name="Setswana">Tswana - tn (Setswana)</option>
                                        <option value="to"{{ old('code') == 'to' ? 'selected' : '' }}
                                            data-language-name="Tonga Islands- faka Tonga">Tonga (Tonga Islands) - to (faka
                                            Tonga)</option>
                                        <option value="tr"{{ old('code') == 'tr' ? 'selected' : '' }}
                                            data-language-name="Türkçe">Turkish - tr (Türkçe)</option>
                                        <option value="ts"{{ old('code') == 'ts' ? 'selected' : '' }}
                                            data-language-name="Xitsonga">Tsonga - ts (Xitsonga)</option>
                                        <option value="tt"{{ old('code') == 'tt' ? 'selected' : '' }}
                                            data-language-name="татарча, tatarça, تاتارچا">Tatar - tt (татарча, tatarça,
                                            تاتارچا‎)</option>
                                        <option value="tw"{{ old('code') == 'tw' ? 'selected' : '' }}
                                            data-language-name="Twi">Twi - tw (Twi)</option>
                                        <option value="ty"{{ old('code') == 'ty' ? 'selected' : '' }}
                                            data-language-name="Reo Tahiti">Tahitian - ty (Reo Tahiti)</option>
                                        <option value="ug"{{ old('code') == 'ug' ? 'selected' : '' }}
                                            data-language-name="Uyƣurqə, ئۇيغۇرچە">Uighur, Uyghur - ug (Uyƣurqə, ئۇيغۇرچە‎)
                                        </option>
                                        <option value="uk"{{ old('code') == 'uk' ? 'selected' : '' }}
                                            data-language-name="українська">Ukrainian - uk (українська)</option>
                                        <option value="ur"{{ old('code') == 'ur' ? 'selected' : '' }}
                                            data-language-name="اردو">Urdu - ur (اردو)</option>
                                        <option value="uz"{{ old('code') == 'uz' ? 'selected' : '' }}
                                            data-language-name="zbek, Ўзбек, أۇزبېك">Uzbek - uz (zbek, Ўзбек, أۇزبېك‎)
                                        </option>
                                        <option value="ve"{{ old('code') == 've' ? 'selected' : '' }}
                                            data-language-name="Tshivenḓa">Venda - ve (Tshivenḓa)</option>
                                        <option value="vi"{{ old('code') == 'vi' ? 'selected' : '' }}
                                            data-language-name="Tiếng Việt">Vietnamese - vi (Tiếng Việt)</option>
                                        <option value="vo"{{ old('code') == 'vo' ? 'selected' : '' }}
                                            data-language-name="Volapük">Volapük - vo (Volapük)</option>
                                        <option value="wa"{{ old('code') == 'wa' ? 'selected' : '' }}
                                            data-language-name="Walon">Walloon - wa (Walon)</option>
                                        <option value="cy"{{ old('code') == 'cy' ? 'selected' : '' }}
                                            data-language-name="Cymraeg">Welsh - cy (Cymraeg)</option>
                                        <option value="wo"{{ old('code') == 'wo' ? 'selected' : '' }}
                                            data-language-name="Wollof">Wolof - wo (Wollof)</option>
                                        <option value="fy"{{ old('code') == 'fy' ? 'selected' : '' }}
                                            data-language-name="Frysk">Western Frisian - fy (Frysk)</option>
                                        <option value="xh"{{ old('code') == 'xh' ? 'selected' : '' }}
                                            data-language-name="isiXhosa">Xhosa - xh (isiXhosa)</option>
                                        <option value="yi"{{ old('code') == 'yi' ? 'selected' : '' }}
                                            data-language-name="ייִדיש">Yiddish - yi (ייִדיש)</option>
                                        <option value="yo"{{ old('code') == 'yo' ? 'selected' : '' }}
                                            data-language-name="Yorùbá">Yoruba - yo (Yorùbá)</option>
                                        <option value="za"{{ old('code') == 'za' ? 'selected' : '' }}
                                            data-language-name="Saɯ cueŋƅ, Saw cuengh">Zhuang, Chuang - za (Saɯ cueŋƅ, Saw
                                            cuengh)</option>
                                    </select>

                                </div>
                                <div class="form-group mb-3">
                                    <label for="layout" class="col-form-label">{{ trans('labels.layout') }} <span
                                            class="text-danger"> *
                                        </span></label>
                                    <select name="layout" class="form-select layout-dropdown" id="layout" required>
                                        <option value="" selected>{{ trans('labels.select') }}</option>
                                        <option value="1"{{ old('layout') == '1' ? 'selected' : '' }}>
                                            {{ trans('labels.ltr') }}</option>
                                        <option value="2"{{ old('layout') == '2' ? 'selected' : '' }}>
                                            {{ trans('labels.rtl') }}</option>
                                    </select>

                                </div>
                                <div class="form-group mb-3">
                                    <label for="layout" class="col-form-label">{{ trans('labels.image') }} <span
                                            class="text-danger"> *
                                        </span></label>
                                    <input type="file" class="form-control" name="image" required>

                                </div>
                            </div>
                        </div>
                        <div class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                            <a href="{{ URL::to('admin/language-settings') }}"
                                class="btn btn-danger px-sm-4">{{ trans('labels.cancel') }}</a>
                            <button
                                @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif
                                class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_language_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'add') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            "user strict";
            $(".code-dropdown").change(function() {
                $('#language').val($(this).find(':selected').attr('data-language-name'));
            }).change();
        });
    </script>
@endsection
