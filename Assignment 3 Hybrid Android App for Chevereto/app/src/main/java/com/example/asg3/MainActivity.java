package com.example.asg3;

import android.os.Bundle;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import android.webkit.WebView;
import android.webkit.WebSettings;
import android.webkit.WebViewClient;
import android.content.Intent;
import android.widget.Toast;

public class MainActivity extends AppCompatActivity {

    private WebView myWebView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        myWebView = findViewById(R.id.webview);
        setupWebView();

        myWebView.setWebViewClient(new WebViewClient() {
            @Override
            public void onPageFinished(WebView view, String url) {
                super.onPageFinished(view, url);
                hideWebElements();
            }

            @Override
            public boolean shouldOverrideUrlLoading(WebView view, String url) {
                if (url.startsWith("http://10.0.2.2:8080/logout")) {
                    runOnUiThread(() -> Toast.makeText(MainActivity.this, "Successfully logout!", Toast.LENGTH_LONG).show());
                    Intent intent = new Intent(MainActivity.this, LoginActivity.class);
                    startActivity(intent);
                    finish();
                    return true;
                }
                return false;
            }
        });

        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        findViewById(R.id.menu_button).setOnClickListener(view -> toggleMenu());
        findViewById(R.id.upload_button).setOnClickListener(view -> goToUploadPage());
    }

    private void setupWebView() {
        WebSettings webSettings = myWebView.getSettings();
        webSettings.setJavaScriptEnabled(true);

        myWebView.setWebViewClient(new WebViewClient() {
            @Override
            public void onPageFinished(WebView view, String url) {
                super.onPageFinished(view, url);
                hideWebElements();
            }
        });

        myWebView.loadUrl("http://10.0.2.2:8080");
    }

    private void hideWebElements() {
        String js = "javascript:(function() { " +
                "var topBars = document.querySelectorAll('.top-bar-left, .top-bar-right');" +
                "topBars.forEach(function(bar) { bar.style.display='none'; });" +
                // Remove upload button from menu
                "var uploadButton = document.querySelector('li[data-action=\"top-bar-upload\"]');" +
                "if (uploadButton) uploadButton.style.display='none';" +
                "})()";
        myWebView.evaluateJavascript(js, null);
    }

    private void toggleMenu() {
        myWebView.evaluateJavascript("javascript:(function() { " +
                "var menuButton = document.querySelector('li[data-action=\"top-bar-menu-full\"]');" +
                "if (menuButton) {" +
                "    menuButton.click();" +
                "} else {" +
                "    console.error('Menu button not found');" +
                "}" +
                "})()", null);
    }

    private void goToUploadPage() {
        myWebView.loadUrl("http://10.0.2.2:8080/upload");
    }
}
