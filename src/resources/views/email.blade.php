<style>
    .container {
        padding: 5px;
        background-color: #F7F8F9;
    }

    .content {
        width: 600px;
        min-width: 600px;
        background-color: white;
    }

    .banner {
        margin-bottom: 5px;
    }

    .inner-content {
        padding-left: 15px;
        padding-right: 15px;
        padding-bottom: 15px;
    }

    .action-button {
        background-color: #e09f3e;
        color: black;
        box-sizing: border-box;
        position: relative;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        cursor: pointer;
        outline: none;
        border: none;
        -webkit-tap-highlight-color: transparent;
        display: inline-block;
        white-space: nowrap;
        text-decoration: none;
        vertical-align: baseline;
        text-align: center;
        margin-top: 5px;
        margin-bottom: 5px;
        min-width: 64px;
        line-height: 36px;
        padding: 0 16px;
        border-radius: 4px;
        overflow: visible;
        transform: translate3d(0, 0, 0);
        transition: background 400ms cubic-bezier(0.25, 0.8, 0.25, 1), box-shadow 280ms cubic-bezier(0.4, 0, 0.2, 1);
        font-family: Roboto, "Helvetica Neue", sans-serif;
        font-size: 14px;
        font-weight: bold;
        width: 570px;
    }

    .text-left {
        text-align: left;
    }

    .text-center {
        text-align: center;
    }

    .action-button.left, .action-button.right {
        width: 278px;
    }

    .action-button.left {
        margin-right: 5px;
    }

    .action-button.right {
        margin-left: 5px;
    }
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class='container'>
    <tr>
        <td align="center">
            <div class='content'>
                <img src='{{env('FRONT_URL')}}/assets/images/banner.png' class='banner'/>
                <br/>
                <div class='inner-content'>
                    @yield('content')
                </div>
            </div>
        </td>
    </tr>
</table>
