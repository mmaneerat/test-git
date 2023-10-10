const path = require("path");
const webpack = require("webpack");
const { DefaultDeserializer } = require("v8");
const HtmlWebpackPlugin = require("html-webpack-plugin");
const CopyPlugin = require("copy-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const BundleAnalyzerPlugin =
  require("webpack-bundle-analyzer").BundleAnalyzerPlugin;
const dbconn = require("json-loader!./../../dbconn.json");
const navmenu = require("json-loader!./../../navmenu.json");

const pageIndexTemplate = {
  template: path.join(__dirname, "src/views/index.php"),
};

const pageAdminTemplate = {
  template: path.join(__dirname, "src/views/admin.php"),
};

const pageBlankTemplate = {
  template: path.join(__dirname, "src/views/blank.php"),
};

let pageWebpackOptionConfig = {
  title: navmenu.headernavbar,
  inject: false,
  mobile: true,
  lang: "th-TH",
  // favicon: path.join(__dirname, "public/images/icon/adj.ico"),
  scriptLoading: "blocking",
  chunksSortMode: "auto",
  meta: [
    { charset: "utf-8" },
    { "http-equiv": "X-UA-Compatible", content: "IE=edge" },
    {
      name: "viewport",
      content: "width=device-width, initial-scale=1, shrink-to-fit=no",
    },
    { name: "theme-color", content: "#0d6efd" },
    { name: "keywords", content: "meta tag for keywords" },
    {
      name: "description",
      content: "A better default template for html-webpack-plugin.",
    },
  ],
};

let config = {
  entry: {
    app: {
      import: [
        "./src/js/main.js",
        "./src/js/vendor/bootstrap.js",
        "./src/js/vendor/fontawesome.js",
      ],
    },
    inputId: {
      import: [
        "./src/js/inputId.js",
    ],
    }, 
    datepiker: {
      import: [
        "./vendor.bundle.js",
    ],
    },
    ajaxfiles: {
      import: [
        "./src/js/ajaxfiles.js",
        "./src/js/vendor/ajax.js",
    ],
    },
    copytext: {
      import: [
        "./src/js/copytext.js",
    ],
    },
  },
  plugins: [
    // new BundleAnalyzerPlugin(),
    new MiniCssExtractPlugin({
      filename: "[name].css",
    }),
    new CopyPlugin({
      patterns: [
        { from: "src/includes", to: "includes" },
        { from: "src/views/components", to: "components" },
        { from: "src/views/pages", to: "pages" },
      ],
    }),
    new HtmlWebpackPlugin({
      filename: "404.php",
      templateParameters: { pageTitle: navmenu.notfound },
      chunks: ["app"],
      template: path.join(__dirname, "src/views/404.php"),
      ...pageWebpackOptionConfig,
    }),
    new HtmlWebpackPlugin({
      filename: "debug.php",
      templateParameters: {
        pageTitle: navmenu.notfound,
        pageLoaded: "debug.php",
      },
      chunks: ["app"],
      ...pageIndexTemplate,
      ...pageWebpackOptionConfig,
    }),
    new HtmlWebpackPlugin({
      filename: "index.php",
      templateParameters: {
        pageTitle: navmenu.home,
        pageLoaded: "front.php",
      },
      chunks: ["app", "inputId","datepiker"],
      excludeChunks: ["copytext","ajaxfiles"],
      ...pageIndexTemplate,
      ...pageWebpackOptionConfig,
    }),
    new HtmlWebpackPlugin({
      filename: "data.php",
      templateParameters: {
        pageTitle: navmenu.search,
        pageLoaded: "data.php",
      },
      chunks: ["app","copytext"],
      excludeChunks: ["addname","datepiker","ajaxfiles"],
      ...pageIndexTemplate,
      ...pageWebpackOptionConfig,
    }),
   
    // Provides jQuery for other JS bundled with Webpack
    new webpack.ProvidePlugin({
      $: "jquery",
      jQuery: "jquery",
    }),
  ],
  output: {
    filename: "scripts/[name].js",
    path: path.resolve(__dirname, "dist"),
    clean: true,
  },
  devServer: {
    server: "http",
    compress: true,
    port: 8080,
    client: {
      overlay: true,
    },
    proxy: {
      context: () => true,
      target: "http://localhost/SAP-search_rename/dist",
      changeOrigin: true,
    },
  },
  resolve: {
    alias: {
      node_modules: path.resolve(__dirname, "node_modules"),
    },
  },
  module: {
    rules: [
      {
        mimetype: "image/svg+xml",
        scheme: "data",
        type: "asset/resource",
        generator: {
          filename: "icons/[hash].svg",
        },
      },
      {
        test: /\.txt$/i,
        use: "raw-loader",
      },
      {
        test: /\.(scss)$/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
          },
          {
            loader: "css-loader",
          },
          {
            loader: "postcss-loader",
            options: {
              postcssOptions: {
                plugins: () => [require("autoprefixer")],
              },
            },
          },
          {
            loader: "sass-loader",
          },
        ],
      },
      // Images
      {
        test: /\.(?:ico|gif|png|jpg|jpeg)$/i,
        type: "asset/resource",
      },
      // Fonts and SVGs
      {
        test: /\.(woff(2)?|eot|ttf|otf|svg|)$/,
        type: "asset/inline",
      },
      // any other rules
      // {
      //   // Exposes jQuery for use outside Webpack build
      //   test: require.resolve('jquery'),
      //   use: [{
      //     loader: 'expose-loader',
      //     options: 'jQuery'
      //   },{
      //     loader: 'expose-loader',
      //     options: '$'
      //   }]
      // }
    ],
  },
  optimization: {
    runtimeChunk: "single",
  },
};

if (process.env.NODE_ENV === "production") {
  pageWebpackOptionConfig.push({
    hash: true,
  });
}

module.exports = (env, argv) => {
  console.log(env);
  console.log(argv);
  // console.log(config);
  let DBconfig = {
    DB_USER:
      argv.mode == "production" ? dbconn.prod.DB_USER : dbconn.dev.DB_USER,
    DB_PASSWORD:
      argv.mode == "production"
        ? dbconn.prod.DB_PASSWORD
        : dbconn.dev.DB_PASSWORD,
    DB_NAME: dbconn.dev.DB_NAME.main,
    DB_SSO: dbconn.dev.DB_NAME.sso,
    DB_HOST: dbconn.dev.DB_HOST,
  };

  if (argv.mode === "production") {
    DBconfig = {
      DB_USER: dbconn.prod.DB_USER,
      DB_PASSWORD: dbconn.prod.DB_PASSWORD,
      DB_NAME: dbconn.prod.DB_NAME.main,
      DB_SSO: dbconn.prod.DB_NAME.sso,
      DB_HOST: dbconn.prod.DB_HOST,
    };
  }

  config.plugins.push(
    new HtmlWebpackPlugin({
      filename: "includes/functions.php",
      templateParameters: { ...DBconfig },
      template: path.join(__dirname, "src/config/functions.php"),
      excludeChunks: ["app","datepiker","copytext","ajaxfiles","inputId"],
    })
  );


  if (argv.mode === "development") {
    config.devtool = "source-map";
  }

  if (argv.mode === "production") {
    config.optimization = {
      runtimeChunk: "single",
      minimize: true,
      minimizer: [new CssMinimizerPlugin()],
    };
  }

  return config;
};
