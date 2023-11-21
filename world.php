<?php
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all countries
    $stmt = $conn->query("SELECT * FROM countries");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get country name from GET parameter and sanitize
    $getCountry = trim(filter_input(INPUT_GET, 'country', FILTER_SANITIZE_STRING));

    // Query to retrieve country information based on the provided country name
    $countryStmt = $conn->prepare("SELECT * FROM countries WHERE name LIKE :country");
    $countryStmt->bindValue(':country', "%$getCountry%", PDO::PARAM_STR);
    $countryStmt->execute();
    $country_x = $countryStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get context from GET parameter and sanitize
    $getContext = trim(filter_input(INPUT_GET, 'context', FILTER_SANITIZE_STRING));

    // Query to retrieve cities information based on the provided country name
    $citiesStmt = $conn->prepare("SELECT cities.name, cities.district, cities.population FROM cities JOIN countries ON countries.code = cities.country_code WHERE countries.name LIKE :country");
    $citiesStmt->bindValue(':country', "%$getCountry%", PDO::PARAM_STR);
    $citiesStmt->execute();
    $cities_x = $citiesStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Handle database connection error
    echo "Connection failed: " . $e->getMessage();
    exit();
}

?>

<?php if (isset($_GET['country']) && !isset($_GET['context'])): ?>
    <table>
        <tr>
            <th>Country Name</th>
            <th>Continent</th>
            <th>Independence Year</th>
            <th>Head of State</th>
        </tr>
        
        <tbody>
            <?php foreach ($country_x as $cntry): ?>
                <tr>
                    <td><?= $cntry['name']; ?></td>
                    <td><?= $cntry['continent']; ?></td>
                    <td><?= $cntry['independence_year']; ?></td>
                    <td><?= $cntry['head_of_state']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php elseif (isset($_GET['country']) && isset($_GET['context'])): ?>
    <table>
        <tr>
            <th>Name</th>
            <th>District</th>
            <th>Population</th>
        </tr>

        <tbody>
            <?php foreach ($cities_x as $cty): ?>
                <tr>
                    <td><?= $cty['name']; ?></td>
                    <td><?= $cty['district']; ?></td>
                    <td><?= $cty['population']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
