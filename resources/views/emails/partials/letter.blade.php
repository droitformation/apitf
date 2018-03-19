<table border="0" width="100%"  align="center" cellpadding="0" cellspacing="0">
    <tr><td height="30"></td></tr>
    <tr>
        <td width="100%" align="center" valign="top" bgcolor="#ffffff">

            <!-- -------   top header   ---------- -->
            <table border="0" width="600" cellpadding="0" cellspacing="0" align="center" class="container">
                <tr bgcolor="43637c"><td height="14"></td></tr>
                <tr bgcolor="43637c">
                    <td align="center">
                        <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="container-middle">
                            <tr>
                                <td>
                                    <table border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="top-header-left">
                                        <tr>
                                            <td align="center">
                                                <table border="0" cellpadding="0" cellspacing="0" class="date">
                                                    <tr>
                                                        <td><img editable="true" mc:edit="icon1" width="13" height="13" style="display: block;" src="{{ asset('newsletter/img/icon-cal.png') }}" alt="icon 1" /></td>
                                                        <td>&nbsp;&nbsp;</td>
                                                        <td mc:edit="date" style="color: #fefefe; font-size: 11px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;">{{ $date }}</td>
                                                    </tr>

                                                </table>
                                            </td>
                                        </tr>
                                    </table><!-- end icon calendar and date -->
                                    <table border="0" align="left" width="10" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="top-header-right">
                                        <tr><td width="10" height="20"></td></tr>
                                    </table>
                                    <table border="0" align="right" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="top-header-right">
                                        <tr>
                                            <td align="center">
                                                <table border="0" cellpadding="0" cellspacing="0" align="center" class="tel">
                                                    <tr>
                                                        <td><img editable="true" mc:edit="icon2" width="17" height="12" style="display: block;" src="{{ asset('newsletter/img/icon-email.png') }}" alt="icon 2" /></td>
                                                        <td>&nbsp;&nbsp;</td>
                                                        <td mc:edit="tel" style="color: #fefefe; font-size: 11px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;">
                                                            <a style="color:#fff;text-decoration: none !important;" href="mailto:info@droitpourlepraticien.ch">info@droitpourlepraticien.ch</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr bgcolor="43637c"><td height="10"></td></tr>
            </table>

            <!-- --------    end top header    ---------- -->


            <!-- --------   main content--------- -->
            <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="container" bgcolor="bccfdb">
                <!-- ------- Header  -------- -->
                <tr bgcolor="bccfdb"><td height="20"></td></tr>
                <tr align="center">
                    <td>
                        <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="container-middle">
                            <tr>
                                <td align="left">
                                    <a href="{{ url('/') }}" style="display: block;text-decoration: none !important; border-style: none !important; border: 0 !important;">
                                        <img editable="true" class="logo" mc:edit="logo" width="270" height="35" border="0" style="display: block;" src="{{ asset('newsletter/img/logo.png') }}" alt="logo" />
                                    </a>
                                </td>
                                <td align="right">
                                    <a href="http://www.unine.ch" target="_blank" style="display: block; border-style: none !important; border: 0 !important;">
                                        <img style="display: block;" class="unine_logo"  width="95" height="65" src="{{ asset('newsletter/img/unine.png') }}" alt="unine" />
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr align="center">
                    <td>
                        <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container-middle">
                            <tr>
                                <td align="left" mc:edit="navigation" style="font-size: 14px; font-family: Helvetica, Arial, sans-serif;">
                                    <a style="text-decoration: none !important; color: #0f4060" href="{{ url('/') }}"><strong>Accueil</strong></a>
                                </td>
                            </tr>
                            <tr><td height="10"></td></tr>
                        </table>
                    </td>
                </tr>
                <!-- -------- end header ------- -->

                <!-- ------- main section ------- -->
                <tr align="center">
                    <td bgcolor="bccfdb">
                        <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="container-middle">
                            <tr bgcolor="ffffff"><td height="14"></td></tr>
                            <tr bgcolor="ffffff"><td align="center">
                                    <a href="{{ url('/') }}">
                                        <img style="display: block;" mc:edit="main-img" class="main-image" width="538" height="180" src="{{ asset('newsletter/img/main-img.png') }}" alt="dernières publications du TF" /></a>
                                </td></tr>
                            <tr bgcolor="ffffff"><td height="20"></td></tr>
                            <tr bgcolor="ffffff">
                                <td>
                                    <table width="528" border="0" align="center" cellpadding="0" cellspacing="0" class="mainContent">
                                        <tr>
                                            <td mc:edit="title1" class="main-header" style=" text-align:center; color: #0f4060; font-size: 16px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;">
                                                Semaine du <strong>{{ $week }}</strong>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr bgcolor="ffffff"><td align="center" height="14" width="560"></td></tr>
                        </table>
                    </td>
                </tr><!-- ------- end main section ------- -->

                <tr mc:repeatable="campaigner" mc:variant="vertical space"><td height="35"></td></tr>

                @if(!$arrets->isEmpty())
                    @foreach($arrets as $arret)
                        @include('emails.partials.arret', ['arret' => $arret])
                    @endforeach
                @endif

            </table>

            <!-- ---------- end main Content --------------- -->

            <!-- -------- Ads  ------- -->
            <table border="0" width="600" cellpadding="0" cellspacing="0" class="container" align="center">

                <tr bgcolor="43637c"><td height="15"></td></tr>

                <tr bgcolor="43637c">
                    <td align="center">

                        <table width="560" border="0" align="center" cellpadding="0" cellspacing="0" class="mainContent">
                            <tr bgcolor="0f4060">
                                <td align="center" height="32" style="font-size: 14px; font-family: Helvetica, Arial, sans-serif; color:#fff;font-weight:bold; border-style: none !important; border: 0 !important;">
                                    Accès au site
                                </td>
                            </tr>
                            <tr bgcolor="ddedf6"><td height="10"></td></tr>
                            <tr bgcolor="ddedf6">
                                <td>
                                    <table border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="section-item">
                                        <tr><td height="6"></td></tr>
                                        <tr>
                                            <td>
                                                <a target="_blank" href="{{ $more }}" style="width: 140px; display: block; border-style: none !important; border: 0 !important;">
                                                    <img editable="true" mc:edit="image1" width="140px" height="181" border="0" style="display: block;" src="{{ asset('newsletter/img/ads.png') }}" alt="image1" class="section-img2" />
                                                </a>
                                            </td>
                                        </tr>
                                        <tr><td height="10"></td></tr>
                                    </table>


                                    <table border="0" width="400" align="right" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="section-item">
                                        <tr><td height="10"></td></tr>
                                        <tr>
                                            <td width="10px"></td>
                                            <td mc:edit="subtitle2" style="color: #0f4060; line-height: 22px; font-size: 12px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;">
                                                Avec l’achat, pour seulement CHF 79, de la dernière édition de l’ouvrage <strong>Le droit pour le praticien</strong>, vous obtenez l'accès au site <a target="_blank" style="color:#0f4060 !important;" href="<?php echo $more; ?>">www.droitpraticien.ch</a>
                                            </td>
                                            <td width="10px"></td>
                                        </tr>
                                        <tr><td height="10"></td></tr>
                                        <tr>
                                            <td width="10px"></td>
                                            <td mc:edit="subtitle2" style="color: #083451; line-height: 22px; font-size: 12px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;">
                                                Durant l’année en cours vous pourrez alors:<br/>

                                                <ul style="margin-left:5px;padding-left:15px;">
                                                    <li>Consulter les résumés de jurisprudence classées par thèmes</li>
                                                    <li>Rechercher les contenus par mots clés</li>
                                                    <li>Créer des alertes emails</li>
                                                </ul>
                                            </td>
                                            <td width="10px"></td>

                                        </tr>
                                        <tr>
                                            <td width="10px"></td>
                                            <td style="color: #0f4060; line-height: 22px; font-size: 12px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;">

                                                <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                                                    <tr>
                                                        <td align="left">
                                                            <div><!--[if mso]>
                                                                <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://" style="height:24px;v-text-anchor:middle;width:110px;" arcsize="17%" strokecolor="#093450" fillcolor="#0f4060">
                                                                    <w:anchorlock/>
                                                                    <center style="color:#ffffff;font-family:sans-serif;font-size:12px;font-weight:normal;">En savoir plus</center>
                                                                </v:roundrect>
                                                                <![endif]--><a target="_blank" href="<?php echo $more; ?>"
                                                                               style="background-color:#0f4060;border:1px solid #093450;border-radius:4px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:12px;font-weight:normal;line-height:24px;text-align:center;text-decoration:none;width:110px;-webkit-text-size-adjust:none;mso-hide:all;">
                                                                    En savoir plus</a></div>
                                                        </td>
                                                        <td align="left">
                                                            <div><!--[if mso]>
                                                                <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://" style="height:24px;v-text-anchor:middle;width:110px;" arcsize="17%" strokecolor="#732131" fillcolor="#912137">
                                                                    <w:anchorlock/>
                                                                    <center style="color:#ffffff;font-family:sans-serif;font-size:12px;font-weight:normal;">Obtenir le livre</center>
                                                                </v:roundrect>
                                                                <![endif]--><a target="_blank" href="http://publications-droit.ch"
                                                                               style="background-color:#912137;border:1px solid #732131;border-radius:4px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:12px;font-weight:normal;line-height:24px;text-align:center;text-decoration:none;width:110px;-webkit-text-size-adjust:none;mso-hide:all;">
                                                                    Obtenir le livre</a></div>
                                                        </td>

                                                    </tr>
                                                </table>

                                            </td>
                                            <td width="10px"></td>
                                        </tr>
                                    </table>


                                </td>
                            </tr>

                            <tr bgcolor="ddedf6"><td height="15"></td></tr>

                        </table>



                    </td>
                </tr>

                <tr bgcolor="43637c"><td height="14"></td></tr>
            </table>
            <!-- -------  end ads ------- -->

            <!-- -------- footer  ------- -->
            <table border="0" width="600" cellpadding="0" cellspacing="0" class="container" align="center">
                <tr bgcolor="43637c"><td height="7"></td></tr>
                <tr bgcolor="43637c">
                    <td align="center" mc:edit="copy1" style="color: #d9e6ef; font-size: 13px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;" class="prefooter-header">
                        Vous recevez cette newsletter depuis le site <a style="color: #fff;" href="{{ url('/') }}">www.droitpraticien.ch</a>
                    </td>
                </tr>
                <tr bgcolor="43637c"><td height="5"></td></tr>
                <tr bgcolor="43637c">
                    <td align="center" mc:edit="copy1" style="color: #d9e6ef; font-size: 13px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;" class="prefooter-header">
                        Pour vous désinscrire de cette newsletter <a style="color: #fff;" href="{{ $unsuscribe }}">cliquez ici</a>
                    </td>
                </tr>
                <tr bgcolor="43637c"><td height="20"></td></tr>
                <tr bgcolor="43637c">
                    <td mc:edit="copy3" align="center" style="color: #fff; line-height:18px; font-size: 12px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;">
                        <strong>Faculté de droit</strong><br/>
                        Avenue du 1er-Mars 26<br/>
                        2000 Neuchâtel
                    </td>
                </tr>
                <tr bgcolor="43637c"><td height="10"></td></tr>
                <tr bgcolor="43637c">
                    <td mc:edit="copy3" align="center" style="color: #fff; font-size: 10px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;">
                        Droit pour le Praticien © Copyright {{ date('Y') }}
                    </td>
                </tr>
                <tr bgcolor="43637c"><td height="14"></td></tr>
            </table>
            <!-- -------  end footer ------- -->

        </td>
    </tr>

    <tr><td height="30"></td></tr>

</table>