<?php

include('wp-config.php');

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
}catch (Exception $ex) {
    echo $ex->getMessage();
}

$table_name = $table_prefix . 'posts';
$sql_select = "SELECT ID, post_content, post_excerpt FROM {$table_name}";

$rows = $pdo->query($sql_select);
while( $row = $rows->fetch() ):
    
    $content = $row['post_content'];
    $new_content = str_replace ( 'src="http://www.webdesignby.com/' , 'src ="/', $content, $count );
    $excerpt = $row['post_excerpt'];
    $new_excerpt = str_replace ( 'src="http://www.webdesignby.com/' , 'src ="/', $excerpt, $count2 );
    
    if( $count || $count2 ):
        
        $id = $row['ID'];
    
        if( $count ):
            try
                {
                    $stmt_update = $pdo->prepare("UPDATE {$table_name} SET post_content= :post_content WHERE ID = :id");
                    $stmt_update->bindParam('id', $id);
                    $stmt_update->bindParam('post_content', $new_content);
                    $stmt_update->execute();  
                }
                catch(Exception $e)
                {
                    echo $e->getMessage();
                }
                
        elseif($count2):

            try
                {
                    $stmt_update = $pdo->prepare("UPDATE {$table_name} SET post_excerpt= :post_excerpt WHERE ID = :id");
                    $stmt_update->bindParam('id', $id);
                    $stmt_update->bindParam('post_excerpt', $new_excerpt);
                    $stmt_update->execute();  
                }
                catch(Exception $e)
                {
                    echo $e->getMessage();
                }
        endif;
        
       
        echo $count . " occurrences replaced in content.<br>{$count2} occurrences replaced in excerpt.";
        echo "<p>" . htmlentities( $content ) . "</p>";
        echo "<p>" . htmlentities( $new_content ) . "</p>";
        echo "<p>" . htmlentities( $excerpt ) . "</p>";
        echo "<p>" . htmlentities( $new_excerpt ) . "</p>";
        echo "<hr>";
    endif;
    
endwhile;
