## Download and Install Vagrant http://www.vagrantup.com/downloads.html

### install Vagrant plugins

```
vagrant plugin install vagrant-vbguest vagrant-cachier vagrant-hostsupdater vagrant-host-shell
```

## Install Ansible

```
sudo apt-add-repository ppa:rquillo/ansible
sudo apt-get update
sudo apt-get install ansible
```

## Install Virtual Box

https://www.virtualbox.org/wiki/Download_Old_Builds_4_3 (tested on VirtualBox 4.3.8 )

## Install packages required for Network File System

```
sudo apt-get install nfs-kernel-server nfs-common portmap
```

## Run vagrant

```
cd /var/www/sichevskyi-uawebchallenge-semifinal-2014/
vagrant up
```

## Now you should be able to use resources

- http://recruiting.192.168.42.20.xip.io
- http://db.192.168.42.20.xip.io

## How to connect to virtual machine via ssh

```
cd /var/www/sichevskyi-uawebchallenge-semifinal-2014/
vagrant ssh
```
