<?php
require 'db_conn.php'; 

$query = $_GET['query'] ?? '';
$query = $conn->real_escape_string($query); 

$limit = 10; 
$sql = "
    SELECT 
        *,
        TIMESTAMPDIFF(YEAR, birthday, CURDATE()) AS age 
    FROM inmates 
    WHERE (firstname LIKE '%$query%' 
        OR lastname LIKE '%$query%' 
        OR middlename LIKE '%$query%' 
        OR suffix LIKE '%$query%'
        OR case_number LIKE '%$query%'
        OR case_detail LIKE '%$query%')
    AND status = 'detained'
    ORDER BY date_admitted DESC
    LIMIT $limit
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr class='hover:bg-gray-300' data-id='{$row['id']}'>";
        echo "<th>{$row['id']}</th>"; 
        echo "<td>{$row['lastname']}</td>";
        echo "<td>{$row['firstname']}</td>";
        echo "<td>{$row['middlename']}</td>";
        echo "<td>{$row['suffix']}</td>";
        echo "<td>" . ($row['birthday'] ? $row['age'] : 'N/A') . "</td>";
        echo "<td>{$row['gender']}</td>";   
        echo "<td>{$row['case_number']}</td>";
        echo "<td>{$row['case_detail']}</td>";
        echo "<td>{$row['date_admitted']}</td>";
        echo "<td>{$row['date_release']}</td>";
        echo "<td>
                <div class='dropdown dropdown-bottom dropdown-end'>
                    <div tabindex='0' role='button' class='btn btn-accent btn-sm m-1 text-black'>Modify</div>
                    <ul tabindex='0' class='dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow flex gap-2'>
                        <li>
                            <button class='btn btn-sm btn-info' onclick='viewInmatePage({$row['id']})'>
                                <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                                    <path d='M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z' />
                                    <path fill-rule='evenodd' d='M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z' clip-rule='evenodd' />
                                </svg>
                                <p>View</p>
                            </button>
                        </li>
                       <li>
                            <button class='btn btn-sm btn-accent' onclick='transferInmate({$row['id']})'>
                                <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                                    <path fill-rule='evenodd' d='M12 5.25c1.213 0 2.415.046 3.605.135a3.256 3.256 0 0 1 3.01 3.01c.044.583.077 1.17.1 1.759L17.03 8.47a.75.75 0 1 0-1.06 1.06l3 3a.75.75 0 0 0 1.06 0l3-3a.75.75 0 0 0-1.06-1.06l-1.752 1.751c-.023-.65-.06-1.296-.108-1.939a4.756 4.756 0 0 0-4.392-4.392 49.422 49.422 0 0 0-7.436 0A4.756 4.756 0 0 0 3.89 8.282c-.017.224-.033.447-.046.672a.75.75 0 1 0 1.497.092c.013-.217.028-.434.044-.651a3.256 3.256 0 0 1 3.01-3.01c1.19-.09 2.392-.135 3.605-.135Zm-6.97 6.22a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 1 0 1.06 1.06l1.752-1.751c.023.65.06 1.296.108 1.939a4.756 4.756 0 0 0 4.392 4.392 49.413 49.413 0 0 0 7.436 0 4.756 4.756 0 0 0 4.392-4.392c.017-.223.032-.447.046-.672a.75.75 0 0 0-1.497-.092c-.013.217-.028.434-.044.651a3.256 3.256 0 0 1-3.01 3.01 47.953 47.953 0 0 1-7.21 0 3.256 3.256 0 0 1-3.01-3.01 47.759 47.759 0 0 1-.1-1.759L6.97 15.53a.75.75 0 0 0 1.06-1.06l-3-3Z' clip-rule='evenodd' />
                                </svg>
                                <p>Transfer</p>
                            </button>
                        </li>
                    </ul>
                </div>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='12' class='text-center'>No inmates found.</td></tr>";
}
?>