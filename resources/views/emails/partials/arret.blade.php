<!-- ------- section 1 ------- -->
<tr align="center">
    <td>
        <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="container-middle">
            <tr bgcolor="fafafa">
                <td>
                    <table width="528" border="0" align="center" cellpadding="0" cellspacing="0" class="mainContent">
                        <tr><td height="20"></td></tr>
                        <tr>
                            <td>
                                <table border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="section-item">
                                    <tr><td height="6"></td></tr>
                                    <tr>&nbsp;
                                        <td>
                                            <a target="_blank" href="{{ $arret->link }}" style="width: 130px; display: block; border-style: none !important; border: 0 !important;">
                                                <img editable="true" mc:edit="image1" width="130" height="101" border="0" style="display: block;" src="{{ asset('newsletter/img/image1.png') }}" alt="image1" class="section-img" />
                                            </a>
                                        </td>
                                    </tr>
                                    <tr><td height="10"></td></tr>
                                </table>

                                <table border="0" width="10" align="left" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" cellspacing="0">
                                    <tr><td height="30" width="10"></td></tr>
                                </table>

                                <table border="0" width="360" align="right" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="section-item">
                                    <tr>
                                        <td mc:edit="title2" style="color: #0f4060; font-size: 16px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;">
                                            <a target="_blank" href="{{ $arret->link }}" style="color: #0f4060; font-size: 16px;text-decoration: none !important; font-weight: bold; font-family: Helvetica, Arial, sans-serif;">
                                               {{ $arret->numero }}
                                            </a>

                                        </td>
                                    </tr>
                                    <tr><td height="15"></td></tr>
                                    <tr>
                                        <td mc:edit="subtitle2" style="color: #0f4060; line-height: 22px; font-size: 12px; font-weight: normal; font-family: Helvetica, Arial, sans-serif;">

                                            Date de décision: {{ $arret->decision_at->formatLocalized("%A %d %B %Y") }}<br/>
                                            {{ $arret->categorie->name }}<br/>
                                            {{ $arret->remarque }}

                                        </td>
                                    </tr>
                                    <tr><td height="15"></td></tr>
                                    <tr>
                                        <td>
                                            <a target="_blank" href="{{ url('/') }}/?page_id=22539&arret={{ $arret->numero }}" style="display: block; width: 130px; border-style: none !important;text-decoration: none !important; border: 0 !important;">
                                                <img editable="true" mc:edit="readMoreBtn" width="130" height="24" border="0" style="display: block;" src="{{ asset('newsletter/img/readmore-btn.png') }}" alt="" /></a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr><td height="20"></td></tr>
                    </table>
                </td>
            </tr>

        </table>
    </td>
</tr><!-- ------- end section 1 ------- -->
<tr><td height="35"></td></tr>