```html
<div x-data="data()">
    <span x-text="hello()"></span>
</div>

<script>
function data() {
    return {
        hello: function () {
            return "Hello!"
        }
    }
}
</script>
```
