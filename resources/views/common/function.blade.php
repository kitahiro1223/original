<?php
function links() {
    echo '
        <button class="manager-btn">links</button>
        <div class="links-area">
            <div class="links-back"></div>
            <div class="links">
                <div>Laravelのログイン機能</div>
                    <div>・<a href="login">login</a></div>
                    <div>・<a href="register">register</a></div>
                    <div>・<a href="verify">verify</a></div>
                <br>
                <div>Laravelのパスワード機能</div>
                    <div>・<a href="confirm">confirm</a></div>
                    <div>・<a href="email">email</a></div>
                    <div>・<a href="reset">reset</a></div>
                <br>
                <div>メイン（main）</div>
                    <div>・<a href="login-email">login</a></div>
                    <div>・<a href="/">/</a></div>
                <br>
                <div>新規登録（signup）</div>
                    <div>・<a href="signup">signup</a></div>
                    <div>・<a href="signup-confirm">signup-confirm</a></div>
                    <div>・<a href="signup-complete">signup-complete</a></div>
                <br>
                <div>パスワードリセット（password-reset）</div>
                    <div>・<a href="pass">pass</a></div>
                    <div>・<a href="pass-confirm">pass-confirm</a></div>
                    <div>・<a href="pass-reset">pass-reset</a></div>
                    <div>・<a href="pass-complete">pass-complete</a></div>
                <br>
                <div>所持金（possession）</div>
                    <div>・<a href="possession">possession</a></div>
                    <div>・<a href="po-category">po-category</a></div>
                    <div>・<a href="po-detail">po-detail</a></div>
                    <div>・<a href="po-add">po-add</a></div>
                    <div>・<a href="po-edit">po-edit</a></div>
                <br>
                <div>収入（income）</div>
                    <div>・<a href="income">income</a></div>
                    <div>・<a href="in-category">in-category</a></div>
                    <div>・<a href="in-detail">in-detail</a></div>
                    <div>・<a href="in-add">in-add</a></div>
                    <div>・<a href="in-edit">in-edit</a></div>
                <br>
                <div>支出（expenditure）</div>       
                    <div>・<a href="expenditure">expenditure</a></div>
                    <div>・<a href="ex-category">ex-category</a></div>
                    <div>・<a href="ex-detail">ex-detail</a></div>
                    <div>・<a href="ex-add">ex-add</a></div>
                    <div>・<a href="ex-edit">ex-edit</a></div>
                <br>
                <div>設定（setting）</div>
                    <div>・<a href="setting">setting</a></div>
                    <div>・<a href="account">account</a></div>
                    <div>・<a href="category-kinds">category-kinds</a></div>
                    <div>・<a href="category-edit">category-edit</a></div>
                    <div>・<a href="logout">logout</a></div>
                <br>
            </div>
        </div>
    ';
};