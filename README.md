# PHML
## PHML is PHP to HTML Library

PHML Class
```
echo PHML::div(
[
	'class' => 'container',
	'style' => 'color: red;',
	'id' => 'test'
],PHML::i(
	[
		'style' => 'color: blue;'
	],'Test Text 1'), PHML::i(
	[
		'style' => 'color: green;'
	],'Test Text 2')
);
```

arml is Array to HTML
```
$html = array(
    'div' => [
        'class' => 'container',
        'style' => 'color: red;',
        'id' => 'test',
        'inner' => [
            ['i' => [
                'style' => 'color: blue;',
                'inner' => 'Test Text 1'
            ]],
            ['i' => [
                'style' => 'color: green;',
                'inner' => 'Test Text 2'
            ]]
        ]
    ]
);
echo PHML::arml($html);
```

jsml is json to HTML
```
$json = '
{
    "div": {
        "class": "container",
        "style": "color: red;",
        "id": "test",
        "inner": [
            {
                "i": {
                    "style": "color: blue;",
                    "inner": "Test Text 1"
                }
            },
            {
                "i": {
                    "style": "color: green;",
                    "inner": "Test Text 2"
                }
            }
        ]
    }
}
';
echo PHML::jsml($json);
```

# Output
<div class="container" style="color: red;" id="test"><i style="color: blue;">Test Text 1</i><i style="color: green;">Test Text 2</i></div>
