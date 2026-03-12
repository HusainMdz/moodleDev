# Moodle

<p align="center"><a href="https://moodle.org" target="_blank" title="Moodle Website">
  <img src="https://raw.githubusercontent.com/moodle/moodle/main/.github/moodlelogo.svg" alt="The Moodle Logo">
</a></p>
<br>
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

<br>
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

<br>
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

<br>
<hr>
<br>

#### 3. Setting Up for Other Developers (first time)

```bash
# Freeze Python dependencies
pip freeze > requirements.txt

# Save installed system packages
apt-mark showmanual | xargs dpkg-query -W -f='${Package}=${Version}\n' > system-requirements.txt
```

<br>
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
