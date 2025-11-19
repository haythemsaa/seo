<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur SEO Master Pro</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" style="width: 600px; border-collapse: collapse; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; border-radius: 8px 8px 0 0; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 600;">SEO Master Pro</h1>
                            <p style="color: #ffffff; margin: 10px 0 0; font-size: 16px; opacity: 0.9;">Votre plateforme SEO professionnelle</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #333333; margin: 0 0 20px; font-size: 24px;">Bienvenue {{ $user->name }} ! 🎉</h2>

                            <p style="color: #666666; line-height: 1.6; margin: 0 0 20px; font-size: 16px;">
                                Nous sommes ravis de vous accueillir sur <strong>SEO Master Pro</strong>, la solution complète pour optimiser et suivre le référencement de vos sites web.
                            </p>

                            <p style="color: #666666; line-height: 1.6; margin: 0 0 20px; font-size: 16px;">
                                Votre compte a été créé avec succès et vous êtes maintenant prêt à :
                            </p>

                            <table role="presentation" style="width: 100%; margin: 20px 0;">
                                <tr>
                                    <td style="padding: 15px; background-color: #f8f9fa; border-left: 4px solid #667eea; margin-bottom: 10px;">
                                        <strong style="color: #333333;">✓ Suivre vos positions sur Google</strong><br>
                                        <span style="color: #666666; font-size: 14px;">Surveillez l'évolution de vos mots-clés en temps réel</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 15px; background-color: #f8f9fa; border-left: 4px solid #667eea; margin-bottom: 10px;">
                                        <strong style="color: #333333;">✓ Analyser vos backlinks</strong><br>
                                        <span style="color: #666666; font-size: 14px;">Identifiez et gérez vos liens entrants</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 15px; background-color: #f8f9fa; border-left: 4px solid #667eea;">
                                        <strong style="color: #333333;">✓ Générer des rapports détaillés</strong><br>
                                        <span style="color: #666666; font-size: 14px;">Exportez vos données en PDF, CSV ou Excel</span>
                                    </td>
                                </tr>
                            </table>

                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ config('app.url') }}/dashboard" style="display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 16px;">
                                    Accéder à mon tableau de bord
                                </a>
                            </div>

                            <div style="background-color: #e3f2fd; padding: 20px; border-radius: 6px; margin: 20px 0;">
                                <p style="margin: 0 0 10px; color: #1976d2; font-weight: 600;">🚀 Pour bien démarrer :</p>
                                <ol style="margin: 10px 0; padding-left: 20px; color: #666666;">
                                    <li style="margin-bottom: 8px;">Créez votre premier projet</li>
                                    <li style="margin-bottom: 8px;">Ajoutez vos mots-clés à suivre</li>
                                    <li style="margin-bottom: 8px;">Lancez votre premier audit SEO</li>
                                    <li>Configurez vos notifications</li>
                                </ol>
                            </div>

                            <p style="color: #666666; line-height: 1.6; margin: 20px 0 0; font-size: 14px;">
                                <strong>Votre forfait actuel :</strong> <span style="color: #667eea; font-weight: 600;">{{ ucfirst($user->subscription_plan ?? 'Free') }}</span>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 30px; border-radius: 0 0 8px 8px; text-align: center;">
                            <p style="color: #666666; margin: 0 0 15px; font-size: 14px;">
                                Besoin d'aide ? Consultez notre <a href="{{ config('app.url') }}/docs" style="color: #667eea; text-decoration: none;">documentation</a>
                                ou contactez notre <a href="mailto:support@seo-master-pro.com" style="color: #667eea; text-decoration: none;">support</a>
                            </p>

                            <div style="border-top: 1px solid #e0e0e0; margin: 20px 0; padding-top: 20px;">
                                <p style="color: #999999; margin: 0; font-size: 12px;">
                                    © {{ date('Y') }} SEO Master Pro. Tous droits réservés.<br>
                                    Vous recevez cet email car vous vous êtes inscrit sur notre plateforme.
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
