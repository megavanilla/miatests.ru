<!doctype html>
<html lang="ru">
    <head>

        <title>Тестовое задание от WineStyle.ru</title>

        <!-- META -->

        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <!-- Add jQuery library -->
        <script type="text/javascript" src="./fancybox/lib/jquery-1.10.2.min.js"></script>

        <!-- Add mousewheel plugin (this is optional) -->
        <script type="text/javascript" src="./fancybox/lib/jquery.mousewheel.pack.js?v=3.1.3"></script>

        <!-- Add fancyBox main JS and CSS files -->
        <script type="text/javascript" src="./fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
        <link rel="stylesheet" type="text/css" href="./fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />

        <!-- Add Button helper (this is optional) -->
        <link rel="stylesheet" type="text/css" href="./fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
        <script type="text/javascript" src="./fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

        <!-- Add Thumbnail helper (this is optional) -->
        <link rel="stylesheet" type="text/css" href="./fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
        <script type="text/javascript" src="./fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

        <!-- Add Media helper (this is optional) -->
        <script type="text/javascript" src="./fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>

        <script type="text/javascript">
          $(document).ready(function() {
            $(".gallery").click(function() {
              var this_id = this.id;
              $.fancybox.open([
                  {
                      type: 'image',
                      imageScale : false,
                      href : './generator.php?name='+this_id+'&size=big',
                      title : 'big'
                  }, {
                      type: 'image',
                      imageScale : false,
                      href : './generator.php?name='+this_id+'&size=med',
                      title : 'med'
                  }, {
                      type: 'image',
                      imageScale : false,
                      href : './generator.php?name='+this_id+'&size=min',
                      title : 'min'
                  }, {
                      type: 'image',
                      imageScale : false,
                      href : './generator.php?name='+this_id+'&size=mic',
                      title : 'mic'
                  }
              ], {
                  helpers : {
                      thumbs : {
                          width: 75,
                          height: 50
                      }
                  }
              });
			});
          });
        </script>
    </head>
    <body>
        <div style="width: 500px; margin: 0 auto;">
          <h3>Галерея картнок =)</h3>
          <?php
            $files = array_slice(scandir('./gallery/'), 2);
          ?>
          <?php for($i=0;$i<count($files);$i++){            
              $name = basename($files[$i]);
              $name = (explode('.', $name));
              if($name[1] != 'jpg'){continue;}//Фильтруем только картинки
              $name = $name[0];
            ?>
            <a class="gallery" id="<?php echo $name; ?>" href="javascript:;"><img src="./generator.php?name=<?php echo $name; ?>&size=mic" /></a>
          <?php } ?>
        </div>
        
        <?php
          //Проверка, что находит другие файлы, но выбирает только jpg файлы
        /*
          $files = array_slice(scandir('./gallery/'), 2);
          print('<pre>');
          var_dump($files);
          print('</pre>');
        */
        ?>
    </body>
</html>