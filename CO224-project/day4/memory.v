module testbench;

	reg CLK, RESET;
	
	reg read, write;
	reg [7:0] wData,addr;
	
	wire [7:0]  rData;
	
	wire BUSY;
	
	data_mem myMem(CLK,RESET,read,write,addr,wData,rData,BUSY);

    initial begin
		$dumpfile("test.vcd"); 
		$dumpvars(0, myMem);
		
		#0
		RESET <= 0;
		
		#1
		RESET <= 1;
		CLK <= 0;   
		
		#2
		RESET <= 0;
		
		#4
		addr <= 8'b00000000;
		write <= 1;
		read <= 0;
		wData <= 42;
		
		#30 
		write <= 0;
		read <= 1;
		
		#60
        $display ("Finished %b %b", addr, wData);
        $finish;
    end
	
	always #2 CLK = ~CLK;
	
endmodule


module data_mem(clk,rst,read,write,address,write_data,read_data,busy_wait);

	input           clk,rst,read,write;
	input[7:0]      address,write_data;
	output[7:0]     read_data;

	output	busy_wait;

	reg[7:0]     read_data;
	reg busy;
	integer  i;

	reg [7:0] memory_array [255:0];

	always @(posedge rst) begin
		if (rst)
		begin
			busy<= 0;
			for (i=0;i<256; i=i+1)
				memory_array[i] <= 0;
		end
	end

	always @(negedge clk) begin
			if(!write && read && !busy) begin
				busy <= 1;
				#400;
				$display ("Read %b from  %b", read_data, address);
				read_data = memory_array[address];
				busy <= 0;
			end
			
			if (write && !read && !busy) begin
				busy <= 1;
				#400;
				$display ("Written %b into  %b", write_data, address);
				memory_array[address] = write_data;
				busy <= 0;
			end
	end 
	
	assign busy_wait = busy;
	
endmodule