<?php

use LightSaml\SamlConstants;
// look here for more instructions - https://github.com/codegreencreative/laravel-samlidp
return [
    /*
    |--------------------------------------------------------------------------
    | SAML idP configuration file
    |--------------------------------------------------------------------------
    |
    | Use this file to configure the service providers you want to use.
    | using https://auth0.com/docs/authenticate/single-sign-on/outbound-single-sign-on/configure-auth0-saml-identity-provider
    |
     */
    // Outputs data to your laravel.log file for debugging
    'debug' => false,
    // Define the email address field name in the users table
    'email_field' => 'email',
    // Define the Name ID for the email field.
    'email_name_id' => SamlConstants::NAME_ID_FORMAT_EMAIL,
    // Define the name field in the users table
    'name_field' => 'name',
    // The URI to your login page
    'login_uri' => 'login',
    // Log out of the IdP after SLO
    'logout_after_slo' => env('LOGOUT_AFTER_SLO', false),
    // The URI to the saml metadata file, this describes your idP
    'issuer_uri' => 'saml/metadata',
    // The certificate
    'cert' => env('SAMLIDP_CERT'),
    // Name of the certificate PEM file, ignored if cert is used
    'certname' => 'cert.pem',
    // The certificate key
    'key' => env('SAMLIDP_KEY'),
    // Name of the certificate key PEM file, ignored if key is used
    'keyname' => 'key.pem',
    // Encrypt requests and responses
    'encrypt_assertion' => true,
    // Make sure messages are signed
    'messages_signed' => true,
    // Defind what digital algorithm you want to use
    'digest_algorithm' => \RobRichards\XMLSecLibs\XMLSecurityDSig::SHA1,
    // list of all service providers
    'sp' => [
        'aHR0cHM6Ly9zc28uZXUuYm94eWhxLmNvbS9hcGkvb2F1dGgvc2FtbA==' => [
            'destination' => 'https://dev-2chzmog6napfcdtt.au.auth0.com/samlp/pPTroNbna0sFS1SWQbgJQgCghV2lystc',
            'logout' => '/',
            'certificate' => '-----BEGIN CERTIFICATE-----
MIIDHTCCAgWgAwIBAgIJSlUtlFpMzKrpMA0GCSqGSIb3DQEBCwUAMCwxKjAoBgNV
BAMTIWRldi0yY2h6bW9nNm5hcGZjZHR0LmF1LmF1dGgwLmNvbTAeFw0yNTAxMDgw
MTI3MTNaFw0zODA5MTcwMTI3MTNaMCwxKjAoBgNVBAMTIWRldi0yY2h6bW9nNm5h
cGZjZHR0LmF1LmF1dGgwLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoC
ggEBAKjFbpAhR6NpKL59wPv614LZ23dPSD+zT/jdsraZJFQimXThtvVylwGy8QRJ
0FpfFPoPd9iPHDoWUaY7IJa6zIeHVmZURMPTj4WlRnZsEOlp3Q53yUWRCKNq2UBG
U1cgv0fe7gisZu29/btHOzuQnu1sSO9W87kI0/H/wpZeDDzbzSEZEe1CwFpKvT7g
VO8MTmnz58WhpVz2x5Dto2oY0MwVnECX9/9r85dBXhHoWlHieVRq3G1qx9OY/qxH
jID9JawwpVGPL3ZGjZlxlQBg9PW+kzG98xvU1Pduxnopfk12jKzZGPG7fmJWH9sE
SES7RgDpg+mI3T3qWEvpkGX9vyMCAwEAAaNCMEAwDwYDVR0TAQH/BAUwAwEB/zAd
BgNVHQ4EFgQUHIHhck95+7ubXAjBjE6ylhVlZaUwDgYDVR0PAQH/BAQDAgKEMA0G
CSqGSIb3DQEBCwUAA4IBAQCFifgHkbzjUMKnzuBY8YsItjqE+TX189yzw9zVX/Ka
/3ef6PUHWvWpV5nlyLC5Q4kxz7Gtj3Bl4v2uJ2gGKBpt1QBZzgI1bFTBouggEoni
suWZMaR0SFOb8uKkxKbfeHHU7bW1DwYflGBo98oUdK3xODEXecfIL/Bwhl0BvYeS
ZoqDhGSWpgVSSPXkTfnSCg8Vb45t7xw/IVGM+nIGq33u410+X1Qd04+4sBBl9m3r
Tz2PxFzezhgvUkYEOA3taAsW0Fe+KGdDMPprgQrTRpdx+sSBdZ5umv6ZlGLkwVIZ
xxG++RYwpwUDVjYOURUfk/4lQADNnsUc128kU158xqdv
-----END CERTIFICATE-----',
        ]
        // Base64 encoded ACS URL
        // 'aHR0cHM6Ly9teWZhY2Vib29rd29ya3BsYWNlLmZhY2Vib29rLmNvbS93b3JrL3NhbWwucGhw' => [
        //     // Your destination is the ACS URL of the Service Provider
        //     'destination' => 'https://myfacebookworkplace.facebook.com/work/saml.php',
        //     'logout' => 'https://myfacebookworkplace.facebook.com/work/sls.php',
        //    // SP certificate
        //     'certificate' => '',
        //    // Turn off auto appending of the idp query param
        //     'query_params' => false,
        //    // Turn off the encryption of the assertion per SP
        //     'encrypt_assertion' => false
        // ]
    ],

    // If you need to redirect after SLO depending on SLO initiator
    // key is beginning of HTTP_REFERER value from SERVER, value is redirect path
    'sp_slo_redirects' => [
        // 'https://example.com' => 'https://example.com',
    ],

    // All of the Laravel SAML IdP event / listener mappings.
    'events' => [
        'CodeGreenCreative\SamlIdp\Events\Assertion' => [],
        'Illuminate\Auth\Events\Logout' => ['CodeGreenCreative\SamlIdp\Listeners\SamlLogout'],
        'Illuminate\Auth\Events\Authenticated' => ['CodeGreenCreative\SamlIdp\Listeners\SamlAuthenticated'],
        'Illuminate\Auth\Events\Login' => ['CodeGreenCreative\SamlIdp\Listeners\SamlLogin'],
    ],

    // List of guards saml idp will catch Authenticated, Login and Logout events
    'guards' => ['web'],
];
