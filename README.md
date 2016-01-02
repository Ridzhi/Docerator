#Docerator - create php doc block for generated programs.

##Usage

Docerator supports all tags from http://www.phpdoc.org.
####Example:
```
<?php
$doc = new Docerator();

$doc
    ->license('MIT')
    ->version('1.0', 'Stable')
    ->emptyLine()
    ->author('Danila Ridzhi', 'danilaridzhi@gmail.com')
    ->emptyLine(2)
    ->text('Some long lorem ipsum.
    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet at debitis delectus et maiores! Ad,
    aspernatur eius facilis harum inventore, ipsa itaque iusto laborum libero magnam nobis officia perferendis perspiciatis,
    praesentium quas repudiandae sed similique suscipit ullam voluptates.
    A atque earum fugiat, ipsa officiis rem reprehenderit voluptatem! Alias, dolores, fuga!');

echo $doc;
```
####Output
```
/**
 * @license MIT
 * @version 1.0 Stable
 *
 * @author Danila Ridzhi <danilaridzhi@gmail.com>
 *
 *
 * Some long lorem ipsum.
 * Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet at debitis delectus et maiores! Ad,
 * aspernatur eius facilis harum inventore, ipsa itaque iusto laborum libero magnam nobis officia perferendis perspiciatis,
 * praesentium quas repudiandae sed similique suscipit ullam voluptates.
 * A atque earum fugiat, ipsa officiis rem reprehenderit voluptatem! Alias, dolores, fuga!
 */
```