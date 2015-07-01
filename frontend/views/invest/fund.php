<section class="see-nav">
    <nav>
        <a href="javascript:void(0)" class="current" data-cato="tyj"><span>体验金流水</span></a>
        <a href="javascript:void(0)" data-cato="zy"><span>自有资金流水</span></a>
        <a href="javascript:void(0)" data-cato="sy"><span>收益流水</span></a>
    </nav>
</section>
<section class="profit-content" id="tyj" style="display:none;">
    <table>
        <tr>
            <td>2015.01.06</td>
            <td>+2</td>
        </tr>
        <tr>
            <td>2015.01.06</td>
            <td>+4</td>
        </tr>
        <tr>
            <td>2015.01.06</td>
            <td>+6</td>
        </tr>
        <tr>
            <td>2015.01.06</td>
            <td>+8</td>
        </tr>

    </table>
</section>
<section class="profit-content" id="zy" style="display:none;">
    <table>
        <tr>
            <td>2015.01.06</td>
            <td>入账+2</td>
        </tr>
        <tr>
            <td>2015.01.06</td>
            <td>入账+4</td>
        </tr>
        <tr>
            <td>2015.01.06</td>
            <td>入账+6</td>
        </tr>
        <tr>
            <td>2015.01.06</td>
            <td>入账+8</td>
        </tr>

    </table>
</section>
<section class="profit-content" id="sy" style="display:none;">
    <table>
        <tr>
            <td>2015.01.06</td>
            <td>提现+2</td>
        </tr>
        <tr>
            <td>2015.01.06</td>
            <td>提现+4</td>
        </tr>
        <tr>
            <td>2015.01.06</td>
            <td>提现+6</td>
        </tr>
        <tr>
            <td>2015.01.06</td>
            <td>提现+8</td>
        </tr>

    </table>
</section>
<script>
    var a = function () {
        $(".amsincome-word").width(($(".ams-income").width() - 13) / 2);
        $(".profit-content").css("min-height", $(window).height() - $(".Y-income").height() - $(".ams-income").height() - 101);
        $(".see-nav nav a").width(($(".see-nav nav").width() - 2) / 3);
    }
    $(document).ready(function () {
        a();
        window.onresize = function () {
            a();
        };
        var UA = window.navigator.userAgent;  //使用设备
        var CLICK = "click";
        if (/ipad|iphone|android/.test(UA)) {   //判断使用设备
            CLICK = "tap";
        }
        var catoFram = $(".profit-content");
        var subNav = $(".see-nav nav a");
        catoFram[0].style.display = "block";
        subNav[0].className += " current";
        subNav[CLICK](function () {
            var _this = $(this);
            var id = _this.data("cato");
            var cur = $("#" + id);
            subNav.removeClass("current");
            _this.addClass("current");
            catoFram.hide();
            cur.scrollTop(0);
            cur.show();
        });
    });
</script>
