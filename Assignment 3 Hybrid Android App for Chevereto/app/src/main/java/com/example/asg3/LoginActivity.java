package com.example.asg3;

import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.webkit.JavascriptInterface;
import android.webkit.WebResourceError;
import android.webkit.WebResourceRequest;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import android.util.Log;

public class LoginActivity extends AppCompatActivity {

    private WebView webView;
    private EditText editTextEmail;
    private EditText editTextPassword;
    private Button buttonLogin;
    private boolean loginAttempted = false;
    private boolean triggerInject = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        webView = findViewById(R.id.webview_login);
        webView.getSettings().setJavaScriptEnabled(true);
        webView.addJavascriptInterface(new WebAppInterface(this), "Android");

        editTextEmail = findViewById(R.id.editTextEmail);
        editTextPassword = findViewById(R.id.editTextPassword);
        buttonLogin = findViewById(R.id.buttonLogin);
        buttonLogin.setOnClickListener(v -> performLogin());

        webView.setWebViewClient(new WebViewClient() {
            @Override
            public void onPageStarted(WebView view, String url, Bitmap favicon) {
                super.onPageStarted(view, url, favicon);
                Log.d("LoginActivity", "Page started loading: " + url);
            }

            @Override
            public void onPageFinished(WebView view, String url) {
                super.onPageFinished(view, url);
                Log.d("LoginActivity", "Page finished loading: " + url);

                // Inject JavaScript to submit the login form
                if (url.endsWith("/login")) {
                    if (triggerInject == true) {
                        runOnUiThread(() -> Toast.makeText(LoginActivity.this, "Wrong Username/Email password combination", Toast.LENGTH_LONG).show());
                        triggerInject = false;
                    }
                    injectLoginScript();
                } else if (!url.equals("http://10.0.2.2:8080/login")) {
                    runOnUiThread(() -> Toast.makeText(LoginActivity.this, "Successfully login!", Toast.LENGTH_LONG).show());
                    navigateToMainActivity();
                }
            }

            @Override
            public void onReceivedError(WebView view, WebResourceRequest request, WebResourceError error) {
                super.onReceivedError(view, request, error);
                Log.e("LoginActivity", "Page error: " + error.toString());
                Toast.makeText(LoginActivity.this, "Error loading page", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void performLogin() {
        webView.loadUrl("http://10.0.2.2:8080/login");
        loginAttempted = true;
    }

    private void injectLoginScript() {
        if (!loginAttempted) {
            return;
        }

        final String email = editTextEmail.getText().toString();
        final String password = editTextPassword.getText().toString();

        String js = "javascript: {" +
                "document.getElementsByName('login-subject')[0].value = '" + email + "';" +
                "document.getElementsByName('password')[0].value = '" + password + "';" +
                "document.querySelector('button[type=submit]').click();" +
                "window.setTimeout(function() {" +
                "  var growl = document.getElementById('growl');" +
                "  var loginError = growl && growl.style.display === 'block' && growl.textContent.includes('Wrong Username/Email password combination');" +
                "  Android.notifyLoginResult(!loginError);" +
                "}, 3000);" + // Delay for 3 seconds to allow for the notification to show up
                "}";
        webView.evaluateJavascript(js, value -> {
            // Log the result of the script execution
            Log.d("LoginActivity", "Login script executed: " + value);
            triggerInject = true;
            loginAttempted = false;
        });
    }


    private void navigateToMainActivity() {
        Intent intent = new Intent(LoginActivity.this, MainActivity.class);
        startActivity(intent);
        finish();
    }

    private class WebAppInterface {
        LoginActivity mContext;

        WebAppInterface(LoginActivity c) {
            mContext = c;
        }

        @JavascriptInterface
        public void notifyLoginResult(final boolean success) {
            mContext.runOnUiThread(() -> {
                if (success) {
                    navigateToMainActivity();
                } else {
                    Toast.makeText(mContext, "Login failed, please check your credentials.", Toast.LENGTH_SHORT).show();
                }
            });
        }
    }

}
