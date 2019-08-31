module main;

    reg [7:0] A;
    output [7:0] R;
    reg CLK;
    
    compTwo myComp(R,A,CLK);
    
    initial begin
        #10
		A <= 8'b00000111;
	
		#20
		$display("A=%b Output=%b \n", A, R);
		$finish;
    end
    
    always #1 CLK = ~CLK;
    
endmodule

module compTwo(OUT,IN,CLK);
   
    input [7:0] IN;     // Input Signal
    input CLK;          
    output [7:0] OUT;   // 2's complemented output signal
    
    reg [7:0] R;

    always @(IN) begin
        R <= -1*IN;
    end
    
    assign OUT = R;

endmodule
