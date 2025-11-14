<?php
header('Content-Type: application/json');

// استلام البيانات من الذكاء الاصطناعي
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

// التحقق
if (!isset($data["title"]) || !isset($data["links"])) {
    echo json_encode(["status" => "error", "msg" => "Missing title or links"]);
    exit;
}

// مسار قاعدة البيانات
$dbFile = "movies.json";

// إذا الملف ما موجود، ننشئه
if (!file_exists($dbFile)) {
    file_put_contents($dbFile, json_encode(["movies" => []], JSON_PRETTY_PRINT));
}

$db = json_decode(file_get_contents($dbFile), true);

$newMovie = [
    "title"       => $data["title"],
    "description" => $data["description"] ?? "",
    "poster"      => $data["poster"] ?? "",
    "links"       => $data["links"],
    "added_at"    => date("Y-m-d H:i:s")
];

$db["movies"][] = $newMovie;

file_put_contents($dbFile, json_encode($db, JSON_PRETTY_PRINT));

echo json_encode(["status" => "success", "msg" => "Movie Added", "movie" => $newMovie]);
?>
