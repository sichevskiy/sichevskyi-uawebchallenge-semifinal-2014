# -*- mode: ruby -*-
# vi: set ft=ruby :
# All Vagrant configuration is done here. For a complete reference,
# please see the online documentation at vagrantup.com.

VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.boot_timeout = 300

  config.vm.define "recruiting" do |recruiting|
	recruiting.vm.box = "recruiting"
	# Configure some Virtual Box params
	recruiting.vm.provider :virtualbox do |recruiting|
		recruiting.customize ["modifyvm", :id, "--name", "UWCrecruiting"]
		recruiting.customize ["modifyvm", :id, "--ostype", "Ubuntu_64"]
		recruiting.customize ["modifyvm", :id, "--memory", "512"]
		recruiting.customize ["modifyvm", :id, "--cpuexecutioncap", "80"]
		# Set VirtualBox guest CPU count to the number of host cores
		# recruiting.customize ["modifyvm", :id, "--cpus", `grep "^processor" /proc/cpuinfo | wc -l`.chomp ]
	end
	recruiting.vm.box_url = "http://cloud-images.ubuntu.com/vagrant/precise/current/precise-server-cloudimg-amd64-vagrant-disk1.box"
        recruiting.vm.network "private_network", ip: "192.168.42.20"
        recruiting.vm.network "forwarded_port", guest: 80, host: 8090
        recruiting.vm.synced_folder "c:/sichevskyi-uawebchallenge-semifinal-2014", "/var/www/sichevskyi-uawebchallenge-semifinal-2014", owner: "www-data", group: "www-data", :mount_options => ["dmode=777","fmode=666"]
        recruiting.vm.synced_folder "c:/xdebug", "/var/www/xdebug", owner: "www-data", group: "www-data", :mount_options => ["dmode=777","fmode=666"]

        # PLUGINS
        # Set entries in hosts file
        # https://github.com/cogitatio/vagrant-hostsupdater
        if Vagrant.has_plugin?("vagrant-hostsupdater")
          recruiting.hostsupdater.remove_on_suspend = true
          recruiting.vm.hostname = "192.168.42.20.xip.io"
        end
        if Vagrant.has_plugin?("vagrant-cachier")
          recruiting.cache.auto_detect = true
        end

        # PROVISIONING
		$script = <<SCRIPT
                sudo apt-add-repository ppa:rquillo/ansible -y
                sudo apt-get update -y
                sudo apt-get install ansible -y
SCRIPT

        config.vm.provision "shell", inline: $script
        config.vm.provision "shell" do |sh|
                sh.inline = "ansible-playbook /var/www/sichevskyi-uawebchallenge-semifinal-2014/build/ansible/dev.yml --inventory-file=/var/www/sichevskyi-uawebchallenge-semifinal-2014/build/ansible/dev --connection=local"
        end

  end

end