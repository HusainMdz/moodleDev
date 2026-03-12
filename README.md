# Moodle

<p align="center"><a href="https://moodle.org" target="_blank" title="Moodle Website">
  <img src="https://raw.githubusercontent.com/moodle/moodle/main/.github/moodlelogo.svg" alt="The Moodle Logo">
</a></p>

<br>

####

## Development Setup

### Table of Contents

1. [Instructions](#instructions)
2. [Installations](#1-installations)
   - [Settings](#settings)
   - [Data](#data)
   - [Run](#run)
3. [MKD: Installing Dependencies (first time)](#2-mkd-installing-dependencies-first-time)
   - [Install](#install)
   - [Run MKD](#run-mkd)
4. [Setting Up for Other Developers (first time)](#3-setting-up-for-other-developers-first-time)
5. [Automated Bash Script for fast installation](#automated-bash-script-for-fast-installation)

6. [terminal setup](#terminal-setup)
   - [installing](#terminal-install)
   - [make it default](#make-it-default)
   - [install requirements](#install-requirements)
   - [open terminal config](#open-terminal-settings)
   - [paste code in it](#add-in-it)
   - [restart terminal](#restart-terminal)

<hr>
<br>

### _Instructions_

#### 1. Installations

##### Settings

```bash
export MOODLE_DOCKER_DB=pgsql
export COMPOSE_PROJECT_NAME=moodleNew
export MOODLE_DOCKER_DB_PORT=5432
```

##### Data

```bash
# Clone Moodle repository (change branch as needed, i used MOODLE_405_STABLE )
git clone -b MOODLE_405_STABLE git://git.moodle.org/moodle.git $MOODLE_DOCKER_WWWROOT

# Copy Docker config template
cp config.docker-template.php $MOODLE_DOCKER_WWWROOT/config.php
```

### Run

```bash
bin/moodle-docker-compose up -d
bin/moodle-docker-wait-for-db
```

<hr>
<br>

#### 2. MKD: Installing Dependencies (first time)

##### Install

```bash
# System packages
apt-get update && apt-get install -y python3 python3-pip python3-dev \
    default-libmysqlclient-dev libpq-dev unixodbc-dev

# Python virtual environment
apt-get install -y python3.11-venv
python3 -m venv venv
source venv/bin/activate

# Install Moodle SDK
pip install moodle-sdk
```

##### Run MKD

```bash
mdk init
```

<hr>
<br>

#### 3. Setting Up for Other Developers (first time)

```bash
# Freeze Python dependencies
pip freeze > requirements.txt

# Save installed system packages
apt-mark showmanual | xargs dpkg-query -W -f='${Package}=${Version}\n' > system-requirements.txt
```

<hr>
<br>

#### Automated Bash Script for fast installation

```bash
#!/bin/bash

# Install system packages
xargs -a system-requirements.txt apt-get update
xargs -a system-requirements.txt apt-get install -y

# Activate Python venv and install Python packages
python3 -m venv venv
source venv/bin/activate
pip install -r requirements.txt
```

<hr>

#### terminal setup

### terminal install

```bash
# Install zsh
apt-get update && apt-get install -y zsh git curl

# Install Oh My Zsh
sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"
```

##### Make it default

```bash
# recommended to do also: set the terminal default as zsh suing terminal 3 dots
chsh -s $(which zsh)
```

##### install requirements

```bash
git clone https://github.com/zsh-users/zsh-autosuggestions ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-autosuggestions

git clone https://github.com/zsh-users/zsh-syntax-highlighting.git ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-syntax-highlighting
```

##### open terminal settings

```bash
vim ~/.zshrc
```

##### add in it:

```bash
# If you come from bash you might have to change your $PATH.
# export PATH=$HOME/bin:$HOME/.local/bin:/usr/local/bin:$PATH

# Path to your Oh My Zsh installation.
export ZSH="$HOME/.oh-my-zsh"

# Set name of the theme to load --- if set to "random", it will
# load a random theme each time Oh My Zsh is loaded, in which case,
# to know which specific one was loaded, run: echo $RANDOM_THEME
# See https://github.com/ohmyzsh/ohmyzsh/wiki/Themes
ZSH_THEME="robbyrussell"

# Set list of themes to pick from when loading at random
# Setting this variable when ZSH_THEME=random will cause zsh to load
# a theme from this variable instead of looking in $ZSH/themes/
# If set to an empty array, this variable will have no effect.
# ZSH_THEME_RANDOM_CANDIDATES=( "robbyrussell" "agnoster" )

# Uncomment the following line to use case-sensitive completion.
# CASE_SENSITIVE="true"

# Uncomment the following line to use hyphen-insensitive completion.
# Case-sensitive completion must be off. _ and - will be interchangeable.
# HYPHEN_INSENSITIVE="true"

# Uncomment one of the following lines to change the auto-update behavior
# zstyle ':omz:update' mode disabled  # disable automatic updates
# zstyle ':omz:update' mode auto      # update automatically without asking
# zstyle ':omz:update' mode reminder  # just remind me to update when it's time

# Uncomment the following line to change how often to auto-update (in days).
# zstyle ':omz:update' frequency 13

# Uncomment the following line if pasting URLs and other text is messed up.
# DISABLE_MAGIC_FUNCTIONS="true"

# Uncomment the following line to disable colors in ls.
# DISABLE_LS_COLORS="true"

# Uncomment the following line to disable auto-setting terminal title.
# DISABLE_AUTO_TITLE="true"

# Uncomment the following line to enable command auto-correction.
# ENABLE_CORRECTION="true"

# Uncomment the following line to display red dots whilst waiting for completion.
# You can also set it to another string to have that shown instead of the default red dots.
# e.g. COMPLETION_WAITING_DOTS="%F{yellow}waiting...%f"
# Caution: this setting can cause issues with multiline prompts in zsh < 5.7.1 (see #5765)
# COMPLETION_WAITING_DOTS="true"

# Uncomment the following line if you want to disable marking untracked files
# under VCS as dirty. This makes repository status check for large repositories
# much, much faster.
# DISABLE_UNTRACKED_FILES_DIRTY="true"

# Uncomment the following line if you want to change the command execution time
# stamp shown in the history command output.
# You can set one of the optional three formats:
# "mm/dd/yyyy"|"dd.mm.yyyy"|"yyyy-mm-dd"
# or set a custom format using the strftime function format specifications,
# see 'man strftime' for details.
# HIST_STAMPS="mm/dd/yyyy"

# Would you like to use another custom folder than $ZSH/custom?
# ZSH_CUSTOM=/path/to/new-custom-folder

# Which plugins would you like to load?
# Standard plugins can be found in $ZSH/plugins/
# Custom plugins may be added to $ZSH_CUSTOM/plugins/
# Example format: plugins=(rails git textmate ruby lighthouse)
# Add wisely, as too many plugins slow down shell startup.
plugins=(git zsh-autosuggestions zsh-syntax-highlighting)

source $ZSH/oh-my-zsh.sh

# User configuration

# export MANPATH="/usr/local/man:$MANPATH"

# You may need to manually set your language environment
# export LANG=en_US.UTF-8

# Preferred editor for local and remote sessions
# if [[ -n $SSH_CONNECTION ]]; then
#   export EDITOR='vim'
# else
#   export EDITOR='nvim'
# fi

# Compilation flags
# export ARCHFLAGS="-arch $(uname -m)"

# Set personal aliases, overriding those provided by Oh My Zsh libs,
# plugins, and themes. Aliases can be placed here, though Oh My Zsh
# users are encouraged to define aliases within a top-level file in
# the $ZSH_CUSTOM folder, with .zsh extension. Examples:
# - $ZSH_CUSTOM/aliases.zsh
# - $ZSH_CUSTOM/macos.zsh
# For a full list of active aliases, run `alias`.
#
# Example aliases
# alias zshconfig="mate ~/.zshrc"
# alias ohmyzsh="mate ~/.oh-my-zsh"
```

##### restart terminal

```bash
source ~/.zshrc
```
