[中文简体](README.md) | English

# BlueStar-Web

### Introduces
This is an integrated team official website realized by HTML / CSS + JS + PHP, which integrates the functions of forum, virtual host, api opening and team information management.

> **Attention**
> <br>Forum, the virtual host **is still under development**，
> <br>**Please read this readme carefully** before using it.。

### Architectures
There are three program entrances in this project, namely:

1. `./index.php` **This is the entrance to the home page.**
2. `./admin/index.php` **This is the administrator panel entrance.**
3. `./api/{The API program to be called}`

### Installations

1. Download warehouse Zip file;
2. Unzip the Zip to the virtual host;
3. After decompression, visit `./index.php` and open the installation interface;
4. Input database information, STMP information, official website information, etc;
5. Click `安装`;
6. After the installation, you can use it to your heart's content!

> **Tips**
> <br>If you want to reinstall, please change the contents of the file `./page/install/install.lock` to `1`.

### Tutorials
- Enter the management terminal `./admin/index.php`, and you can modify it according to the menu item.
- Go to the preview terminal `./index.php` to see what the current homepage looks like.

### Contributes

1.  Fork this Repositorie;
2.  Create your own branch (e.g. feat_xxx)
3.  Commit the code you want to contribute;
4.  Create a Pull Request to apply for contribution.