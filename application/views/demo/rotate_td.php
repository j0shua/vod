<table cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td>ชื่อ</td>
        <td><div class="rotate">1000000 00000 000 kg</div></td>
        <td><div class="rotate">10kg</div></td>
        <td><div class="rotate">10kg</div></td>

    </tr>
    <tr>

        <td>G</td>
        <td>H</td>
        <td>I</td>
        <td>J</td>
    </tr>
    <tr>

        <td>L</td>
        <td>M</td>
        <td>N</td>
        <td>O</td>
    </tr>


</table>
<style type="text/css">
    td {
        border-collapse:collapse;
        border: 1px black solid;
    }
    tr:nth-of-type(5) td:nth-of-type(1) {
        visibility: hidden;
    }
    .rotate {
        -moz-transform: rotate(-90.0deg);  /* FF3.5+ */
        -o-transform: rotate(-90.0deg);  /* Opera 10.5 */
        -webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
        filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
        -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
    }
</style>
<script>
    $(document).ready(function() {
        $('.rotate').css('height', $('.rotate').width());
        //$('.rotate').css('width','25px');
    });
</script>