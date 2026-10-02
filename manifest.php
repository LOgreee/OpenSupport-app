<?php require("config.php");

header('Content-Type: application/manifest+json; charset=utf-8');

$baseUrl = rtrim($opensupport_link, '/');
$appName = "OpenSupport";

$manifest = [
    "name"             => $appName. " - " .$opensupport_domain,
    "short_name"       => $appName,
    "description"      => "Helpdesk powered by OpenSupport",
    "start_url"        => $baseUrl . "/dashboard/",
    "scope"            => $baseUrl . "/",
    "display"          => "standalone",
    "background_color" => "#f8fafc",
    "theme_color"      => "#0f172a",
    "icons"            => [
        [
            "src"     => $baseUrl . "/src/opensupport_assets/opensupport_icon_192.png",
            "sizes"   => "192x192",
            "type"    => "image/png",
            "purpose" => "any maskable"
        ],
        [
            "src"     => $baseUrl . "/src/opensupport_assets/opensupport_icon_512.png",
            "sizes"   => "512x512",
            "type"    => "image/png",
            "purpose" => "any maskable"
        ]
    ]
];

echo json_encode($manifest, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
exit();