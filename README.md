# Usage

```
$bible = new BibleGateway('ESV') // Version optional. Default is ESV

$bible->reference = 'John 3:16' // Starts the search

echo $bible->text // Echos the verse text
```
The above will output 
```
<p>
  <span id="en-NIV-26137" class="text John-3-16">
    <sup class="versenum">16&nbsp;</sup>For God so loved the world that he gave his one and only Son, that whoever believes in him shall not perish but have eternal life.
  </span>
</p>
```

The API uses the same classes that the Bible Gateway website uses
```
<span class="chapternum"></span> // Chapter number
<sup class="versenum"></sup> // Verse number
<span class="woj"></span> // Word of Jesus, can be used for red text
```
