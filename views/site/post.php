<h1>Список статей</h1>
<a href="signup"> sign</a>
<ol>
   <?php
   foreach ($posts as $post) {
       echo '<li>' . $post->title . '</li>';

   }
   ?>
</ol>
