<div class="ui mini modal">
    <div class="header" style="display: flex; align-items: center; font-weight: bold;">
        <img class="ui mini image" src="img/logo.png" alt="Logo" style="display: inline-block; margin-right: 1.5rem; vertical-align: middle;">Login
    </div>
    <div class="content">
        <form class="ui form" method="post">
            <input type="hidden" name="log-action" value="login">
            <div class="field">
                <input type="text" name="username", placeholder="Username">
            </div>
            <div class="field">
                <input type="password" name="password", placeholder="Password">
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input id="remember" class="hidden" type="checkbox" name="remember" tabindex="0">
                    <label for="remember">Remember me</label>
                </div>
            </div>
            <div class="ui basic buttons">
                <button class="ui button" type="submit" name="submit-login">Submit</button>
            </div>
        </form>
    </div>
</div>