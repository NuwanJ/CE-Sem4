module main;

    reg [7:0] PC;   
    reg CLK, RESET;
	wire BUSY;

	reg read, write;
	reg [7:0] dataIn;
	reg [7:0] dataAddress;
	
	wire[7:0]  dataOut;
	
	data_mem myMemory(CLK,RESET,read,write,dataAddress,dataOut, dataIn,BUSY);
	
	initial begin
		$dumpfile("out.vcd"); 
		$dumpvars(0, myMemory);

	    #0
		CLK <= 0;
		RESET <= 0;
		PC <= 0;
		dataAddress <= 0;
		dataIn <= 0;
		write <= 0;
		read <= 0;
		
		#200
		dataAddress <= 0;
		dataIn <= 10;
		write <= 1;
		read <= 0;
		
		#400
		dataAddress <= 1;
		dataIn <= 11;
		write <= 1;
		read <= 0;
		
		#600
		dataAddress <= 0;
		write <= 0;
		read <= 1;
		
		#800
		dataAddress <= 1;
		write <= 0;
		read <= 1;
		
		#2000 
		$finish;
    end

    always begin
        #5 CLK = ~CLK;
    end 

    always begin
        #10 PC  = PC + 1;
    end 
endmodule

module data_mem(CLK,RESET, readEn, writeEn, dataAddress,  dataOut, dataIn , BUSY);

	input           CLK, RESET, readEn, writeEn;
	input [7:0] 	dataAddress;
	input [7:0] 	dataIn;
	output reg [7:0] 	dataOut;
	output reg			BUSY;
	integer  i;

	reg [7:0] memory[255:0];

	// reset the memory values @ positive edge of the RESET signal
	always @(posedge RESET) begin
		for (i=0;i<256; i=i+1)
			memory[i] <= 0;
		BUSY<= 0;
	end

	always @(dataAddress or posedge writeEn) begin
			// Reading data from the memory
		if(!writeEn && readEn && !BUSY) begin
			BUSY <= 1;
			#50;  // Clock * Cycles
			dataOut <= memory[dataAddress];

			#10
			$display ("MEM: Read %d from  %d", dataOut, dataAddress);
			BUSY <= 0;
				
		end

		if (writeEn && !readEn && !BUSY) begin
			BUSY <= 1;
			#50;  // Clock * Cycles
			memory[dataAddress] <= dataIn[7:0];
				
			#10
			$display ("MEM: Written %d into  %d", dataIn, dataAddress);
			BUSY <= 0;
				
		end
	end
	
	initial begin
	    // Initialize the memory
       	BUSY<= 0;
		for (i=0;i<256; i=i+1)
			memory[i] <= 0;
	end
endmodule


